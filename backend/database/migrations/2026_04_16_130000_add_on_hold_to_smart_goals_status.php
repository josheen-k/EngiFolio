<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE smart_goals DROP CHECK check_smart_goals");
        DB::statement("ALTER TABLE smart_goals MODIFY status ENUM('planned', 'in_progress', 'completed', 'on_hold') NOT NULL DEFAULT 'planned'");
        DB::statement("ALTER TABLE smart_goals ADD CONSTRAINT check_smart_goals CHECK (status IN ('planned', 'in_progress', 'completed', 'on_hold'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE smart_goals DROP CHECK check_smart_goals");
        DB::statement("ALTER TABLE smart_goals MODIFY status ENUM('planned', 'in_progress', 'completed') NOT NULL DEFAULT 'planned'");
        DB::statement("ALTER TABLE smart_goals ADD CONSTRAINT check_smart_goals CHECK (status IN ('planned', 'in_progress', 'completed'))");
    }
};
