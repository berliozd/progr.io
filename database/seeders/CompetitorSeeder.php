<?php

namespace Database\Seeders;

use App\Models\Competitor;
use App\Models\Project;
use Illuminate\Database\Seeder;

class CompetitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Competitor::factory(3)->sequence(
            [
                'name' => fake()->text(15),
                'description' => fake()->text(50),
                'url' => fake()->url(),
                'project_id' => Project::get()->first()->id
            ],
            [
                'name' => fake()->text(15),
                'description' => fake()->text(50),
                'url' => fake()->url(),
                'project_id' => Project::get()->last()->id
            ],
            [
                'name' => fake()->text(15),
                'description' => fake()->text(50),
                'url' => fake()->url(),
                'project_id' => Project::get()->first()->id
            ]
        )->create();
    }
}
