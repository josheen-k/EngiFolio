<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('smart_goals', function (Blueprint $table) {
            // Stores persisted row order from the SMART Goals drag-and-drop UI.
            $table->unsignedInteger('goal_order')->nullable()->after('status');
        });

        // Backfill existing records so old data has a deterministic initial order per plan.
        $planIds = DB::table('smart_goals')
            ->select('plan_id')
            ->distinct()
            ->pluck('plan_id');

        foreach ($planIds as $planId) {
            $goalIds = DB::table('smart_goals')
                ->where('plan_id', $planId)
                ->orderBy('created_at', 'desc')
                ->orderBy('goal_id', 'desc')
                ->pluck('goal_id');

            foreach ($goalIds as $index => $goalId) {
                DB::table('smart_goals')
                    ->where('goal_id', $goalId)
                    ->update(['goal_order' => $index + 1]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('smart_goals', function (Blueprint $table) {
            $table->dropColumn('goal_order');
        });
    }
};
