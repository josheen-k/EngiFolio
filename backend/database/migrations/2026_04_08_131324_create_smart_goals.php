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
            $table->text('timeline')->nullable();
            $table->text('progress_notes')->nullable();
            $table->text('learnings')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->text('completion_notes')->nullable();
            $table->foreignId('goal_status_id')->nullable()->constrained('goal_statuses', 'goal_status_id')->onDelete('set null');
            $table->unsignedInteger('goal_order')->default(0);
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