<?php

namespace Database\Factories;

use App\Models\Competitor;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Competitor>
 */
class CompetitorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->text(15),
            'description' => fake()->text(250),
            'url' => fake()->url(),
            'project_id' => Project::get()->first()->id
        ];
    }
}
