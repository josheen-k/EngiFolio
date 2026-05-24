<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('career_development_plans', function (Blueprint $table) {
            $table->index('profile_id', 'career_development_plans_profile_id_index');
        });

        Schema::table('career_development_plans', function (Blueprint $table) {
            $table->dropUnique('career_development_plans_profile_id_plan_year_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('career_development_plans', function (Blueprint $table) {
            $table->unique(['profile_id', 'plan_year']);
        });

        Schema::table('career_development_plans', function (Blueprint $table) {
            $table->dropIndex('career_development_plans_profile_id_index');
        });
    }
};
