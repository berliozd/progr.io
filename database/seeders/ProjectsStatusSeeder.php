<?php

namespace Database\Seeders;

use App\Models\ProjectsStatus;
use Illuminate\Database\Seeder;

class ProjectsStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProjectsStatus::factory(3)->sequence(
            [
                'label' => 'New',
                'code' => 'new',
            ],
            [
                'label' => 'Idea',
                'code' => 'idea',
            ],
            [
                'label' => 'In progress',
                'code' => 'in_progress',
            ]
        )->create();
    }
}
