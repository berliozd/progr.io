<?php

namespace Database\Factories;

use App\Models\Competitor;
use App\Models\NotesType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompetitorsNote>
 */
class CompetitorsNoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => fake()->text(15),
            'competitor_id' => Competitor::get()->first()->id,
            'note_type_id' => NotesType::get()->first()->id
        ];
    }
}
