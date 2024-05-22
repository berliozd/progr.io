<?php

namespace Database\Seeders;

use App\Models\MetaType;
use Illuminate\Database\Seeder;

class MetaTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MetaType::factory()->create(['name' => 'description']);
        MetaType::factory()->create(['name' => 'keywords']);
    }
}
