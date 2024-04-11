<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Competitor;
use App\Models\CompetitorsNote;
use App\Models\NotesType;
use App\Models\Project;
use App\Models\ProjectsNote;
use App\Models\ProjectsStatus;
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

        $rawData = $request->toArray();
        $data = [
            'user_id' => auth()->user()->id,
            'title' => $rawData['title']['value'] ?? $rawData['title'],
            'description' => $rawData['description']['value'] ?? $rawData['description'],
            'status' => $rawData['status']['value'] ?? $rawData['status']
        ];
        return Project::create($data);
    }

    public function show(string $id)
    {
        $project = Project::with('competitors.notes.type')
            ->with('notes.type')
            ->find($id);

        // Add available note types
        $ids = array_map(function ($projectNote) {
            return $projectNote['note_type_id'];
        }, $project->notes->toArray());
        $availableNotesTypes = NotesType::whereNotIn('id', $ids)->get();
        $project->availableNotesTypes = $availableNotesTypes;

        // Add all note types
        $project->allNotesTypes = NotesType::all();

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
        $this->updateCompetitors($data['competitors']);

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

    public function updateCompetitors($competitors): void
    {
        foreach ($competitors as $competitor) {
            if (empty($competitor['name'] || empty($competitor['description']) || empty($competitor['url']))) {
                continue;
            }
            $projectCompetitor = Competitor::whereId($competitor['id'])->first();
            if ($projectCompetitor !== null) {
                $projectCompetitor->update([
                    'project_id' => $competitor['project_id'],
                    'name' => $competitor['name'],
                    'description' => $competitor['description'],
                    'url' => $competitor['url']
                ]);
            } else {
                CompetitorsNote::create([
                    'project_id' => $competitor['project_id'],
                    'name' => $competitor['name'],
                    'description' => $competitor['description'],
                    'url' => $competitor['url']
                ]);
            }
        }
    }
}
