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
        ProjectsStatus::factory(6)->sequence(
            [
                'label' => 'New',
                'description' => 'This status indicates that the project has just been added to the list.',
            ],
            [
                'label' => 'Under consideration',
                'description' => 'This status indicates that the project idea is being considered and matured.',
            ],
            [
                'label' => 'In progress',
                'description' => 'This status indicates that the project idea is being developed.',
            ],
            [
                'label' => 'Paused',
                'description' => 'This status indicates that the project idea has been temporarily put on hold.',
            ],
            [
                'label' => 'Completed',
                'description' => 'This status indicates that the project idea has been implemented and completed.',
            ],
            [
                'label' => 'Abandoned',
                'description' => 'This status indicates that the project idea has been abandoned for some reason.',
            ],
        )->create();
    }
}
