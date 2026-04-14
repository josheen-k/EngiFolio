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
        Schema::create('smart_goals', function (Blueprint $table) {
            $table->id('goal_id');
            $table->foreignId('plan_id')->constrained('career_development_plans', 'plan_id')->onDelete('cascade');
            $table->text('goal_description');
            $table->text('timeline');
            $table->text('progress_notes');
            $table->text('learnings');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('completion_date');
            $table->text('completion_notes');
            $table->string('status', 25)->default('planned');
            $table->check("status IN ('planned', 'in_progress', 'completed')");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smart_goals');
    }
};
