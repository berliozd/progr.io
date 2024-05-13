<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('competitors_projects');
        Schema::create('competitors_projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('competitor_id');
            $table->unsignedBigInteger('project_id');
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->foreign('competitor_id')
                ->references('id')
                ->on('competitors')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitors_projects');
    }
};
