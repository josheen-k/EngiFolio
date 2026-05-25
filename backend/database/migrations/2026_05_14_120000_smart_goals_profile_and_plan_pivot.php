<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Refactor SMART goals ownership and plan linkage.
 *
 * Before: each goal belonged to exactly one career plan (smart_goals.plan_id).
 * After:  each goal belongs to a student profile (smart_goals.profile_id);
 *         plans link goals through career_development_plan_smart_goal (many-to-many).
 */
return new class extends Migration
{
    public function up(): void
    {
        // --- Phase 1: Add profile_id on smart_goals (nullable until backfilled) ---

        Schema::table('smart_goals', function (Blueprint $table) {
            // Nullable first so existing rows survive before we copy profile_id from plans.
            $table->unsignedBigInteger('profile_id')->nullable()->after('goal_id');
        });

        // Derive profile_id from the plan each goal was attached to (pre-migration data).
        DB::statement('
            UPDATE smart_goals sg
            INNER JOIN career_development_plans cdp ON sg.plan_id = cdp.plan_id
            SET sg.profile_id = cdp.profile_id
        ');

        // Enforce NOT NULL only after every row has a profile_id.
        DB::statement('ALTER TABLE smart_goals MODIFY profile_id BIGINT UNSIGNED NOT NULL');

        Schema::table('smart_goals', function (Blueprint $table) {
            $table->foreign('profile_id')
                ->references('profile_id')
                ->on('student_profiles')
                ->cascadeOnDelete();
        });

        // --- Phase 2: Pivot table for plan <-> goal (replaces direct plan_id on goals) ---

        Schema::create('career_development_plan_smart_goal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')
                ->constrained('career_development_plans', 'plan_id')
                ->cascadeOnDelete();
            $table->foreignId('goal_id')
                ->constrained('smart_goals', 'goal_id')
                ->cascadeOnDelete();
            $table->timestamps();
            // One row per plan–goal pair; same goal may appear on multiple plans.
            $table->unique(['plan_id', 'goal_id']);
        });

        // Preserve existing one-plan-per-goal links before dropping smart_goals.plan_id.
        DB::statement('
            INSERT INTO career_development_plan_smart_goal (plan_id, goal_id, created_at, updated_at)
            SELECT plan_id, goal_id, NOW(), NOW()
            FROM smart_goals
        ');

        // --- Phase 3: Remove legacy direct FK from goals to a single plan ---

        Schema::table('smart_goals', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
            $table->dropColumn('plan_id');
        });
    }

    /**
     * Reverse the migration: restore smart_goals.plan_id and remove profile + pivot.
     *
     * Note: If a goal was linked to multiple plans, only MIN(plan_id) is restored.
     */
    public function down(): void
    {
        Schema::table('smart_goals', function (Blueprint $table) {
            $table->foreignId('plan_id')->nullable()->after('goal_id')
                ->constrained('career_development_plans', 'plan_id')
                ->cascadeOnDelete();
        });

        // Pick one plan per goal when collapsing many-to-many back to a single plan_id.
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
