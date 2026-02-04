<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Services\AutoPopulateService;
use Illuminate\Console\Command;

class SetProjectsMetas extends Command
{
    public function __construct(private readonly AutoPopulateService $autoPopulateService,)
    {
        Parent::__construct();
    }

    protected $signature = 'project:set_metas';

    protected $description = 'Set meta dat for the projects.';

    public function handle()
    {
        \Log::debug('Set meta data for projects');
        $projects = Project::doesntHave('metaDescription')->orDoesntHave('metaKeywords')
            ->limit(5)
            ->orderBy('updated_at')
            ->get();

        if (($projects->count() ?? 0) === 0) {
            \Log::debug('No projects to set meta data');
            return;
        }

        foreach ($projects as $project) {
            \Log::debug('Set meta data for project ' . $project->id);
            $this->autoPopulateService->addMetas($project);
        }
        \Log::debug('Projects meta data set');
    }
}
