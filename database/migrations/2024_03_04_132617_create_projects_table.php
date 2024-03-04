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
        Schema::dropIfExists('projects');
        Schema::dropIfExists('projects_statuses');

        Schema::create('projects_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('label')->nullable(false);
            $table->string('code')->nullable(false);
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable(false);
            $table->longText('description')->nullable(false);
            $table->bigInteger('status')->unsigned();
            $table->foreign('status')
                ->references('id')
                ->on('projects_statuses')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
        Schema::dropIfExists('projects_status');
    }
};
