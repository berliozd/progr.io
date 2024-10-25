<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Services\AutoPopulateService;
use App\Services\SendMailService;
use App\View\Components\CompetitorsFailureEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Blade;

class EnrichProjects extends Command
{
    public function __construct(
        private readonly AutoPopulateService $autoPopulateService,
        private readonly SendMailService $mailService
    ) {
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
            ->limit(2)
            ->orderBy('updated_at')
            ->get();

        if (($projectsWithoutCompetitors->count() ?? 0) === 0) {
            \Log::info('No projects to enrich with competitors');
            return;
        }

        foreach ($projectsWithoutCompetitors as $project) {
            if (!$this->autoPopulateService->canAddCompetitors($project->owner)) {
                if ($project->add_competitors_failure < 3) {
                    $project->update(['add_competitors_failure' => ++$project->add_competitors_failure]);
                    $this->mailService->sendEmail(
                        Blade::renderComponent(
                            new CompetitorsFailureEmail(
                                AutoPopulateService::NB_CREDITS_REQUIRED_COMPETITORS,
                                $project->owner
                            )
                        ),
                        config('app.name') . ' - Competitors could not have been added',
                        $project->owner,
                        true
                    );
                }
                continue;
            }
            try {
                \Log::info('Populate competitors for project ' . $project->id);
                $this->autoPopulateService->addCompetitors($project, true);
                $project->owner->update(
                    [
                        'used_credits' => $project->owner->used_credits + AutoPopulateService::NB_CREDITS_REQUIRED_COMPETITORS,
                        'nb_credits' => $project->owner->nb_credits - AutoPopulateService::NB_CREDITS_REQUIRED_COMPETITORS
                    ]
                );
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
                \Log::info('Add category for project ' . $project->id);
                $this->autoPopulateService->addCategory($project);
                \Log::info('Category for project ' . $project->id . ' added.');
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
            }
        }
        \Log::info('Projects category auto populated');
    }
}
