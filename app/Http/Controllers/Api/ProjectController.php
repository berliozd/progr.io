<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        $project = Project::where('id', $id)->first();

        // Add project notes
        $projectNotes = ProjectsNote::addSelect([
            'note_type_label' => NotesType::select('label')->whereColumn('id', 'projects_notes.note_type_id')->limit(1),
            'note_type_code' => NotesType::select('code')->whereColumn('id', 'projects_notes.note_type_id')->limit(1)
        ])->where('project_id', $project->id)->get();
        $project->notes = $projectNotes;

        // Add available notes types
        $ids = array_map(function ($projectNote) {
            return $projectNote['note_type_id'];
        }, $projectNotes->toArray());
        $availableNotesTypes = NotesType::whereNotIn('id', $ids)->get();
        $project->availableNotesTypes = $availableNotesTypes;

        return $project;
    }

    public function update(Request $request, string $id)
    {
        /** @var Project $project */
        $project = Project::where('id', $id)->first();
        if ($project->owner()->getResults()->id !== auth()->user()->id) {
            throw new \Exception('Not allowed');
        }
        $data = $request->toArray();
        $notes = $data['notes'];
        foreach ($notes as $note) {
            if (empty($note['content'])) {
                continue;
            }
            $projectNote = ProjectsNote::where([
                ['project_id', '=', $note['project_id']],
                ['note_type_id', '=', $note['note_type_id']]
            ])->first();
            if ($projectNote !== null) {
                $projectNote->update([
                    'project_id' => $note['project_id'],
                    'note_type_id' => $note['note_type_id'],
                    'content' => $note['content']
                ]);
            } else {
                ProjectsNote::create([
                    'project_id' => $note['project_id'],
                    'note_type_id' => $note['note_type_id'],
                    'content' => $note['content']
                ]);
            }
        }
        $project->update($data);
        return $data;
    }

    public function destroy(string $id)
    {
        Project::destroy([$id]);
    }
}
