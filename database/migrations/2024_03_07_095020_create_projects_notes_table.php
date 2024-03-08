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
        Schema::dropIfExists('projects_notes');
        Schema::dropIfExists('notes_types');

        Schema::create('notes_types', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('label');
            $table->timestamps();
        });
        Schema::create('projects_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('note_type_id');
            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('note_type_id')
                ->references('id')
                ->on('notes_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unique(['project_id', 'note_type_id']);
            $table->longText('content');
            $table->timestamps();
            $table->index(['project_id', 'note_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects_notes');
    }
};
