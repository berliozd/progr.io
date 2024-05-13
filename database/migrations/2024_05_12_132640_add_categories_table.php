<?php

use Database\Seeders\CategorySeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('categories');
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->longText('description');
            $table->timestamps();
        });
        $seeder = new CategorySeeder();
        $seeder->run();
    }

    public function down(): void
    {
        //
    }
};
