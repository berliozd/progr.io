<?php

namespace Database\Seeders;

use App\Models\Competitor;
use App\Models\CompetitorsNote;
use App\Models\NotesType;
use Illuminate\Database\Seeder;

class CompetitorsNoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CompetitorsNote::factory(3)->sequence(
            [
                'content' => fake()->text(15),
                'competitor_id' => Competitor::get()->first()->id,
                'note_type_id' => NotesType::get()->first()->id
            ],
            [
                'content' => fake()->text(15),
                'competitor_id' => Competitor::get()->first()->id,
                'note_type_id' => NotesType::get()->last()->id
            ],
            [
                'content' => fake()->text(15),
                'competitor_id' => Competitor::get()->last()->id,
                'note_type_id' => NotesType::get()->last()->id
            ]
        )->create();
    }
}
