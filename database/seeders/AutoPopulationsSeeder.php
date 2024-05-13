<?php

namespace Database\Seeders;

use App\Models\AutoPopulations;
use Illuminate\Database\Seeder;

class AutoPopulationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AutoPopulations::factory(4)->sequence(
            ['code' => 'off'], ['code' => 'on'], ['code' => 'processing']
        )->create();
    }
}
