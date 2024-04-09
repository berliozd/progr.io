<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('competitors')) {
            Schema::create('competitors', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable(false);
                $table->longText('description')->nullable(false);
                $table->string('url')->nullable(false);
                $table->timestamps();
                $table->foreignId('project_id')->references('id')
                    ->on('projects')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('competitors')) {
            Schema::drop('competitors');
        }
    }
};
