<?php

use App\Models\ProjectMeta;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table(
            'project_metas',
            function (Blueprint $table) {
                // Add tmp cols
                $table->unsignedBigInteger('tmp_project_id');
                $table->unsignedBigInteger('tmp_type_id');
            }
        );

        // Fill tmp cols
        $allMetas = ProjectMeta::all();
        foreach ($allMetas as $meta) {
            $meta->tmp_project_id = $meta->project_id;
            $meta->tmp_type_id = $meta->type;
            $meta->save();
        }

        Schema::table(
            'project_metas',
            function (Blueprint $table) {
                // Drop foreign keys and recreate them
                $table->dropForeign(['project_id']);
                $table->dropForeign(['type']);
                $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
                $table->foreign('type')->references('id')->on('meta_types')->onDelete('cascade');
            }
        );

        // Fill new foreign cols
        $allMetas = ProjectMeta::all();
        foreach ($allMetas as $meta) {
            $meta->project_id = $meta->tmp_project_id;
            $meta->type = $meta->tmp_type_id;
            $meta->save();
        }

        Schema::table(
            'project_metas',
            function (Blueprint $table) {
                // Drop tmp cols
                $table->dropColumn(['tmp_project_id', 'tmp_type_id']);
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
