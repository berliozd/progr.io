<?php

namespace App\Services;

use App\Events\ProjectCompetitorsPopulated;
use App\Events\ProjectPopulated;
use App\Models\Competitor;
use App\Models\CompetitorsNote;
use App\Models\MetaType;
use App\Models\NotesType;
use App\Models\Project;
use App\Models\ProjectsNote;
use App\Models\User;

class AutoPopulateService
{
    public function __construct(private readonly AIService $aiService)
    {
    }

    public function populate(Project $project): void
    {
        \Log::info('Populate project ' . $project->id);
        $projectOwner = User::where('id', $project->owner->id)->first();
        if (!$this->canAutoPopulate($projectOwner)) {
            \Log::info(
                sprintf(
                    'Projects %s cannot be auto populated because user %s is either not subscribed or does not have enough credits',
                    $project->id,
                    $projectOwner->id
                )
            );
            return;
        }

        $this->addProjectNotes($project);
        $this->addCompetitors($project);
        $project->owner->update(
            ['used_ai_credits' => $project->owner->used_ai_credits + config('app.auto-population-credits')]
        );
        ProjectPopulated::dispatch($project);
        \Log::info(sprintf('Project %s auto populated', $project->id));
    }

    public function canAutoPopulate(User $user, int $nbCreditsNeeded = null): bool
    {
        if ($user->subscription() && $user->subscription()->valid()) {
            return true;
        }
        $nbCreditsLeft = config('app.free-ai-credits') - $user->used_ai_credits;
        return $nbCreditsLeft >= ($nbCreditsNeeded ?? config('app.auto-population-credits'));
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