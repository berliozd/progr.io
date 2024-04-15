<?php

use App\Models\Project;
use App\Models\ProjectsVisibility;
use Database\Seeders\ProjectsVisibilitySeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects_visibilities', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable(false);
            $table->timestamps();
        });
        $seeder = new ProjectsVisibilitySeeder();
        $seeder->run();

        Schema::table('projects', function (Blueprint $table) {
            $table->bigInteger('visibility')->unsigned();
        });
        Project::all()->each(function (Project $project) {
            $project->visibility = ProjectsVisibility::where('code', 'private')->first()->id;
            $project->save();
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->foreign('visibility')
                ->references('id')
                ->on('projects_visibilities')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('visibility');
        });
        Schema::dropIfExists('projects_visibilities');
    }
};
