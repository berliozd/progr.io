<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AutoPopulations;
use App\Models\NotesType;
use App\Models\Project;
use App\Models\ProjectsNote;
use App\Models\ProjectsStatus;
use App\Models\ProjectsVisibility;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return Project::addSelect(
            ['status_label' => ProjectsStatus::select('label')->whereColumn('id', 'projects.status')->limit(1)]
        )
            ->where('user_id', auth()->user()->id)
            ->get();
    }

    public function store(Request $request)
    {
        if (!auth()->user()->subscribed()
            && Project::where('user_id', auth()->user()->id)->count() >= config('app.free-nb-projects')) {
            \Log::debug('User cannot add anymore projects');
            return response()->json([
                'message' => 'Vous avez atteint la limite de projets pour votre plan actuel.',
                'code' => 'LIMIT_EXCEEDED',
            ], 403);
        }

        $data = $request->toArray();
        $data['user_id'] = auth()->user()->id;
        return Project::create($data);
    }

    public function show(string $id)
    {
        $project = Project::with(['competitors.notes.type'])
            ->with('notes.type')
            ->find($id);

        // Add available note types
        $ids = array_map(function ($projectNote) {
            return $projectNote['note_type_id'];
        }, $project->notes->toArray());
        $availableNotesTypes = NotesType::whereNotIn('id', $ids)->get();
        $project->availableNotesTypes = $availableNotesTypes;
        $project->allNotesTypes = NotesType::all();
        $project->allVisibilities = $this->getVisibilities();
        $project->allStatuses = ProjectsStatus::all();
        $project->allAutoPopulations = $this->getAutoPopulations();

        return $project;
    }

    public function update(Request $request, string $id)
    {
        /** @var Project $project */
        $project = Project::whereId($id)->first();
        if ($project->owner()->getResults()->id !== auth()->user()->id) {
            throw new \Exception('Not allowed');
        }
        $data = $request->toArray();

        $project->fill($data);

        $this->updateNotes($data['notes']);
        $this->updateCompetitorsOrder($project, $data['competitors']);

        $project->save();
        return $data;
    }

    public function destroy(string $id)
    {
        Project::destroy([$id]);
    }

    public function updateNotes($notes): void
    {
        foreach ($notes as $note) {
            if (empty($note['content'])) {
                continue;
            }
            $projectNote = ProjectsNote::where([
                ['project_id', '=', $note['project_id']],
                ['note_type_id', '=', $note['type']['id']]
            ])->first();
            if ($projectNote !== null) {
                $projectNote->update([
                    'project_id' => $note['project_id'],
                    'note_type_id' => $note['type']['id'],
                    'content' => $note['content'],
                    'order' => $note['order']
                ]);
            } else {
                ProjectsNote::create([
                    'project_id' => $note['project_id'],
                    'note_type_id' => $note['type']['id'],
                    'content' => $note['content'],
                    'order' => $note['order']
                ]);
            }
        }
    }

    private function updateCompetitorsOrder(Project $project, mixed $competitors): void
    {
        foreach ($competitors as $competitor) {
            $project->competitors()->updateExistingPivot(
                $competitor['id'],
                ['order' => $competitor['pivot']['order']]
            );
        }
    }

    private function getVisibilities(): Collection
    {
        $visibilities = ProjectsVisibility::all();
        foreach ($visibilities as $visibility) {
            $visibility->label = trans('app.project.visibilities.' . $visibility->code);
        }
        return $visibilities;
    }

    private function getAutoPopulations(): Collection
    {
        $autoPopulations = AutoPopulations::where('code', 'on')->orWhere('code', 'off')->get();
        foreach ($autoPopulations as $autoPopulation) {
            $autoPopulation->label = trans('app.project.auto_populations.' . $autoPopulation->code);
        }
        return $autoPopulations;
    }
}
