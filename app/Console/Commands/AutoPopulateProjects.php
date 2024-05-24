<?php

namespace App\Console\Commands;

use App\Models\AutoPopulations;
use App\Models\Project;
use App\Services\AutoPopulateService;
use Illuminate\Console\Command;

class AutoPopulateProjects extends Command
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
    protected $signature = 'project:auto_populate_projects';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto populate the projects set as to be auto-populated.';

    public function handle()
    {
        \Log::info('Auto populating projects');
        $this->autoPopulateService->cleanProjectsToBeAutoPopulated();

        $projects = Project
            ::where('auto_population', AutoPopulations::where('code', 'on')->pluck('id')->first())
            ->limit(2)
            ->orderBy('updated_at')
            ->get();

        if (($projects->count() ?? 0) === 0) {
            \Log::info('No projects to auto-populate');
            return;
        }

        // Set all projects as 'processing' so that they cannot be updated
        foreach ($projects as $project) {
            $project->update(['auto_population' => AutoPopulations::where('code', 'processing')->pluck('id')->first()]);
        }

        foreach ($projects as $project) {
            try {
                $this->autoPopulateService->populate($project);
                $project->update([
                        'auto_population' => AutoPopulations::where('code', 'off')->pluck('id')->first(),
                        'auto_populated_at' => now()
                    ]
                );
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
                $project->update(['auto_population' => AutoPopulations::where('code', 'on')->pluck('id')->first()]);
            }
        }
        \Log::info('Projects auto populated');
    }
}
