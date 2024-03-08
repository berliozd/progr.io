<?php

namespace Database\Seeders;

use App\Models\NotesType;
use App\Models\ProjectsNote;
use Illuminate\Database\Seeder;

class ProjectsNoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $notesTypes = NotesType::all();
        ProjectsNote::factory(6)->sequence(
            [
                'project_id' => 1,
                'note_type_id' => $notesTypes[0]->id,
                'content' => fake()->text(450),
            ],
            [
                'project_id' => 1,
                'note_type_id' => $notesTypes[1]->id,
                'content' => fake()->text(450),
            ],
            [
                'project_id' => 1,
                'note_type_id' => $notesTypes[2]->id,
                'content' => fake()->text(450),
            ],
            [
                'project_id' => 1,
                'note_type_id' => $notesTypes[3]->id,
                'content' => fake()->text(450),
            ],
            [
                'project_id' => 2,
                'note_type_id' => $notesTypes[0]->id,
                'content' => fake()->text(450),
            ],
            [
                'project_id' => 2,
                'note_type_id' => $notesTypes[1]->id,
                'content' => fake()->text(450),
            ]
        )->create();
    }
}
