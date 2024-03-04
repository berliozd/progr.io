<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::factory(3)->sequence(
            [
                'title' => fake()->text(15),
                'description' => fake()->text(250),
                'status' => 1,
                'user_id' => 2,
            ],
            [
                'title' => fake()->text(15),
                'description' => fake()->text(250),
                'status' => 2,
                'user_id' => 2,
            ],
            [
                'title' => fake()->text(15),
                'description' => fake()->text(250),
                'status' => 3,
                'user_id' => 2,
            ]
        )->create();
    }
}
