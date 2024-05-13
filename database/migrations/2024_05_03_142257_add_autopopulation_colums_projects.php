<?php

use App\Models\AutoPopulations;
use App\Models\Project;
use Database\Seeders\AutoPopulationsSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('auto_populations');
        Schema::create('auto_populations', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable(false);
            $table->timestamps();
        });
        $seeder = new AutoPopulationsSeeder();
        $seeder->run();

        Schema::table('projects', function (Blueprint $table) {
            $table->bigInteger('auto_population')->unsigned();
            $table->dateTime('auto_populated_at')->nullable();
        });
        Project::all()->each(function (Project $project) {
            $project->auto_population = AutoPopulations::where('code', 'off')->first()->id;
            $project->auto_populated_at = now();
            $project->updated_at = now();
            $project->save();
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->foreign('auto_population')
                ->references('id')
                ->on('auto_populations')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('auto_population');
            $table->dropColumn('auto_populated_at');
        });
        Schema::dropIfExists('auto_populations');
    }
};
