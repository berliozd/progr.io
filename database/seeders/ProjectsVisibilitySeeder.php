<?php

namespace Database\Seeders;

use App\Models\ProjectsVisibility;
use Illuminate\Database\Seeder;

class ProjectsVisibilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProjectsVisibility::factory(3)->sequence(
            ['code' => 'private'], ['code' => 'public'], ['code' => 'only_members']
        )->create();
    }
}
