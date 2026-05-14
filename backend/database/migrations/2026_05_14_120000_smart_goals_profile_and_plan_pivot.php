<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * SMART goals belong to a student profile; many plans can link the same goal via a pivot.
     */
    public function up(): void
    {
        Schema::table('smart_goals', function (Blueprint $table) {
            $table->unsignedBigInteger('profile_id')->nullable()->after('goal_id');
        });

        DB::statement('
            UPDATE smart_goals sg
            INNER JOIN career_development_plans cdp ON sg.plan_id = cdp.plan_id
            SET sg.profile_id = cdp.profile_id
        ');

        DB::statement('ALTER TABLE smart_goals MODIFY profile_id BIGINT UNSIGNED NOT NULL');

        Schema::table('smart_goals', function (Blueprint $table) {
            $table->foreign('profile_id')
                ->references('profile_id')
                ->on('student_profiles')
                ->cascadeOnDelete();
        });

        Schema::create('career_development_plan_smart_goal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')
                ->constrained('career_development_plans', 'plan_id')
                ->cascadeOnDelete();
            $table->foreignId('goal_id')
                ->constrained('smart_goals', 'goal_id')
                ->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['plan_id', 'goal_id']);
        });

        DB::statement('
            INSERT INTO career_development_plan_smart_goal (plan_id, goal_id, created_at, updated_at)
            SELECT plan_id, goal_id, NOW(), NOW()
            FROM smart_goals
        ');

        Schema::table('smart_goals', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
            $table->dropColumn('plan_id');
        });
    }

    /**
     * Restore plan_id from pivot (one plan per goal) and drop profile + pivot.
     */
    public function down(): void
    {
        Schema::table('smart_goals', function (Blueprint $table) {
            $table->foreignId('plan_id')->nullable()->after('goal_id')
                ->constrained('career_development_plans', 'plan_id')
                ->cascadeOnDelete();
        });

        DB::statement('
            UPDATE smart_goals sg
            INNER JOIN career_development_plan_smart_goal p ON p.goal_id = sg.goal_id
            INNER JOIN (
                SELECT goal_id, MIN(plan_id) AS plan_id
                FROM career_development_plan_smart_goal
                GROUP BY goal_id
            ) x ON x.goal_id = sg.goal_id
            SET sg.plan_id = x.plan_id
        ');

        Schema::dropIfExists('career_development_plan_smart_goal');

        Schema::table('smart_goals', function (Blueprint $table) {
            $table->dropForeign(['profile_id']);
            $table->dropColumn('profile_id');
        });
    }
};
