<?php

namespace Database\Seeders;

use App\Models\NotesType;
use Illuminate\Database\Seeder;

class NotesTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NotesType::factory(6)->sequence(
            [
                'code' => 'benefits',
                'label' => 'benefits for users',
            ],
            [
                'code' => 'monetization',
                'label' => 'ways to monetize',
            ],
            [
                'code' => 'features',
                'label' => 'features',
            ],
            [
                'code' => 'pricing',
                'label' => 'pricing plans',
            ],
            [
                'code' => 'domains',
                'label' => 'domain names',
            ],
            [
                'code' => 'targets',
                'label' => 'targets',
            ],
            [
                'code' => 'competitors',
                'label' => 'competitors',
            ],
        )->create();
    }
}
