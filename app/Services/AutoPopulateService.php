<?php

namespace App\Services;

use App\Events\ProjectCompetitorsPopulated;
use App\Events\ProjectPopulated;
use App\Models\AutoPopulations;
use App\Models\Competitor;
use App\Models\CompetitorsNote;
use App\Models\MetaType;
use App\Models\NotesType;
use App\Models\Project;
use App\Models\ProjectsNote;
use App\Models\User;
use App\View\Components\AutoPopulationFailureEmail;
use Illuminate\Support\Facades\Blade;

class AutoPopulateService
{
    const int NB_CREDITS_REQUIRED_AUTO_POPULATION = 7;
    public const int NB_CREDITS_REQUIRED_COMPETITORS = 15;

    public function __construct(private readonly AIService $aiService, private readonly SendMailService $mailService)
    {
    }

    public function populate(Project $project): void
    {
        \Log::info('Populate project ' . $project->id);
        $this->addProjectNotes($project);
        $project->owner->update([
                'used_credits' => $project->owner->used_credits + AutoPopulateService::NB_CREDITS_REQUIRED_COMPETITORS,
                'nb_credits' => $project->owner->nb_credits - AutoPopulateService::NB_CREDITS_REQUIRED_COMPETITORS
            ]
        );
        ProjectPopulated::dispatch($project);
        \Log::info(sprintf('Project %s auto populated', $project->id));
    }

    private function canAutoPopulate(User $user): bool
    {
        return $user->nb_credits - self::NB_CREDITS_REQUIRED_AUTO_POPULATION >= 0;
    }

    public function canAddCompetitors(User $user): bool
    {
        return $user->nb_credits - self::NB_CREDITS_REQUIRED_COMPETITORS >= 0;
    }

    public function cleanProjectsToBeAutoPopulated(): void
    {
        $projects = Project::where('auto_population', AutoPopulations::where('code', 'on')->pluck('id')->first())
            ->orderBy('updated_at')
            ->get();

        foreach ($projects as $project) {
            if (!$this->canAutoPopulate($project->owner)) {
                $this->mailService->sendEmail(
                    Blade::renderComponent(
                        new AutoPopulationFailureEmail(
                            AutoPopulateService::NB_CREDITS_REQUIRED_AUTO_POPULATION,
                            $project->owner
                        )
                    ),
                    config('app.name') . ' - Auto-population could not have been run',
                    $project->owner,
                    true
                );
                $project->update(['auto_population' => AutoPopulations::where('code', 'off')->pluck('id')->first()]);
                \Log::info(
                    sprintf(
                        'Projects %s cannot be auto populated because user %s is either not subscribed or does not have enough credits',
                        $project->id,
                        $project->owner->id
                    )
                );
            }
        }
    }

    private function addProjectNotes(Project $project): void
    {
        ProjectsNote::destroy(ProjectsNote::whereProjectId($project->id)->pluck('id'));
        $i = 0;
        foreach (NotesType::all() as $type) {
            $note = ProjectsNote::create([
                'project_id' => $project->id,
                'note_type_id' => $type->id,
                'content' => $this->aiService->getNote($project->title, $project->description, $type->code),
                'order' => ++$i
            ]);
            \Log::info('Project note added : ' . $note->id);
        }
    }

    private function addCompetitorNotes(Competitor $competitor): void
    {
        $i = 0;
        foreach (NotesType::whereNotIn('code', ['competitors', 'domains'])->get() as $type) {
            $note = CompetitorsNote::create([
                'competitor_id' => $competitor->id,
                'note_type_id' => $type->id,
                'content' => $this->aiService->getNote($competitor->name, $competitor->description, $type->code, true),
                'order' => ++$i
            ]);
            \Log::info('Competitor note added : ' . $note->id);
        }
    }

    public function addCompetitors(Project $project, bool $dispatchEvent = false): void
    {
        $project->competitors()->detach();

        $competitors = $this->aiService->getCompetitors($project->title, $project->description);
        $i = 0;
        foreach ($competitors as $competitorData) {
            if (!isset($competitorData) || !isset($competitorData[0]) || !isset($competitorData[1]) || !isset($competitorData[2])) {
                \Log::info('Invalid competitor');
                return;
            }
            $competitor = $this->createOrGetExisting($competitorData);
            $project->competitors()->attach(
                $competitor->id,
                ['order' => $i++, 'created_at' => now(), 'updated_at' => now()]
            );
        }
        if ($dispatchEvent) {
            ProjectCompetitorsPopulated::dispatch($project);
        }
    }

    public function createOrGetExisting(mixed $competitorData): Competitor
    {
        $competitorData[2] = rtrim($competitorData[2], '/');
        $existingCompetitor = Competitor::where('url', $competitorData[2])
            ->orWhere('url', str_replace('www.', '', $competitorData[2]))
            ->first();
        if (!$existingCompetitor) {
            \Log::info('Competitor not found with url ' . $competitorData[2]);
            $competitor = Competitor::create([
                'name' => $competitorData[0],
                'description' => $competitorData[1],
                'url' => $competitorData[2]
            ]);
            \Log::info('Competitor added : ' . $competitor->id);
            $this->addCompetitorNotes($competitor);
        } else {
            \Log::info('Competitor found with url ' . $competitorData[2]);
            $competitor = $existingCompetitor;
        }
        return $competitor;
    }

    public function addCategory($project): void
    {
        $categoryId = $this->aiService->getCategoryId($project->title, $project->description);
        $project->category_id = $categoryId;
        $project->save();
    }

    public function addMetas($project): void
    {
        $keywords = $this->aiService->getMeta($project->title, $project->description, 'keywords');
        $project->metaKeywords()->updateOrCreate(
            ['type' => MetaType::where('name', 'keywords')->first()->id],
            ['value' => $keywords]
        );
        $description = $this->aiService->getMeta($project->title, $project->description, 'description');
        $project->metaDescription()->updateOrCreate(
            ['type' => MetaType::where('name', 'description')->first()->id],
            ['value' => $description]
        );
    }
}