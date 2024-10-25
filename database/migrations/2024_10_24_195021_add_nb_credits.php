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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('nb_credits')->default(0);
            $table->integer('used_credits')->default(0);
            $table->dropColumn('one_time_product');
            $table->dropColumn('used_ai_credits');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nb_credits');
            $table->dropColumn('used_credits');
        });
    }
};
