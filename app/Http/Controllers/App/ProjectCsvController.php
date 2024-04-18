<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use League\Csv\Writer;


class ProjectCsvController extends Controller
{
    public function __invoke(Request $request, int $id)
    {

        $project = Project::findOrFail($id);

        $headerLine = ['', 'Project'];
        $i = 0;
        foreach ($project->competitors as $competitor) {
            $headerLine[] = sprintf('Competitor %s', ++$i);
        };

        $nameLine = ['Name', $project->title];
        $descriptionLine = ['Description', $project->description];
        $urlLine = ['Url', ''];
        $competitors = ['Competitors', $this->getNote($project->notes, 'competitors')];
        $monetization = ['Monetization', $this->getNote($project->notes, 'monetization')];
        $benefits = ['Features', $this->getNote($project->notes, 'benefits')];
        $features = ['Features', $this->getNote($project->notes, 'features')];
        $pricing = ['Pricing', $this->getNote($project->notes, 'pricing')];
        $targets = ['Targets', $this->getNote($project->notes, 'targets')];
        $domains = ['Domains', $this->getNote($project->notes, 'domains')];

        foreach ($project->competitors as $competitor) {
            $nameLine[] = $competitor->name;
            $descriptionLine[] = $competitor->description;
            $urlLine[] = $competitor->url;
            $competitors[] = $this->getNote($competitor->notes, 'competitors');
            $monetization[] = $this->getNote($competitor->notes, 'monetization');
            $benefits[] = $this->getNote($competitor->notes, 'benefits');
            $features[] = $this->getNote($competitor->notes, 'features');
            $pricing[] = $this->getNote($competitor->notes, 'pricing');
            $targets[] = $this->getNote($competitor->notes, 'targets');
            $domains[] = $this->getNote($competitor->notes, 'domains');
        };

        $data = [
            $headerLine,
            $nameLine,
            $descriptionLine,
            $urlLine,
            $competitors,
            $monetization,
            $benefits,
            $features,
            $pricing,
            $targets,
            $domains
        ];
        $csv = Writer::createFromString('');
        $csv->insertAll($data);

        $csv->output('export.csv');
    }

    private function getNote(Collection $notes, string $type): string
    {
        $notes = $notes->all();
        foreach ($notes as $note) {
            if ($note->type->code === $type) {
                return $note->content;
            }
        }
        return '';
    }
}
