<?php

namespace App\Console\Commands;

use App\Models\AutoPopulations;
use App\Models\Project;
use App\Services\AutoPopulateService;
use Illuminate\Console\Command;

class EnrichProjects extends Command
{
    public function __construct(private readonly AutoPopulateService $autoPopulateService,)
    {
        Parent::__construct();
    }

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'project:enrich_projects';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enrich projects with competitors, category.';

    public function handle()
    {
        $this->addCompetitors();
        $this->addCategories();
    }

    /**
     * @return void
     */
    public function addCompetitors(): void
    {
        \Log::info('Auto populating projects competitors');

        // Get all projects not set to be auto-populated, or being populated, and without competitors
        $projectsWithoutCompetitors = Project::doesntHave('competitors')
            ->whereNot(
                'auto_population',
                AutoPopulations::where('code', 'on')->pluck('id')->first()
            )
            ->whereNot(
                'auto_population',
                AutoPopulations::where('code', 'processing')->pluck('id')->first()
            )
            ->limit(2)
            ->orderBy('updated_at')
            ->get();

        if (($projectsWithoutCompetitors->count() ?? 0) === 0) {
            \Log::info('No projects to enrich with competitors');
            return;
        }

        foreach ($projectsWithoutCompetitors as $project) {
            try {
                if (!$this->autoPopulateService->canAutoPopulate($project->owner, 1)) {
                    continue;
                }
                \Log::info('Populate competitors for project ' . $project->id);
                $this->autoPopulateService->addCompetitors($project, true);
                $project->owner->update(['used_ai_credits' => $project->owner->used_ai_credits + 1]);
                \Log::info('Competitors for project ' . $project->id . ' populated.');
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
            }
        }
        \Log::info('Projects competitors auto populated');
    }

    private function addCategories(): void
    {
        \Log::info('Auto populating projects category');
        $projectsToAddCategory = Project::doesntHave('category')->limit(5)->get();
        if (($projectsToAddCategory->count() ?? 0) === 0) {
            \Log::info('No projects to add category');
            return;
        }
        foreach ($projectsToAddCategory as $project) {
            try {
                if (!$this->autoPopulateService->canAutoPopulate($project->owner, 1)) {
                    continue;
                }
                \Log::info('Add category for project ' . $project->id);
                $this->autoPopulateService->addCategory($project);
                $project->owner->update(['used_ai_credits' => $project->owner->used_ai_credits + 1]);
                \Log::info('Category for project ' . $project->id . ' added.');
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
            }
        }
        \Log::info('Projects category auto populated');
    }
}
