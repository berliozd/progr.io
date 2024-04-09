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
        Schema::dropIfExists('competitors_notes');
        Schema::create('competitors_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('competitor_id');
            $table->unsignedBigInteger('note_type_id');
            $table->foreign('competitor_id')
                ->references('id')
                ->on('competitors')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('note_type_id')
                ->references('id')
                ->on('notes_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unique(['competitor_id', 'note_type_id']);
            $table->longText('content');
            $table->timestamps();
            $table->index(['competitor_id', 'note_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competitors_notes');
    }
};
