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
        $projects = Project
            ::where('auto_population', AutoPopulations::where('code', 'on')->pluck('id')->first())
            ->limit(2)
            ->orderBy('updated_at')
            ->get();
        foreach ($projects as $project) {
            try {
                $this->autoPopulateService->populate($project);
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
            }
        }
        \Log::info('Projects auto populated');
    }
}
