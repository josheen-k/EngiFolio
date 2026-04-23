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
        Schema::create('goal_action_steps', function (Blueprint $table) {
            $table->id('step_id');
            $table->foreignId('goal_id')->constrained('smart_goals', 'goal_id')->onDelete('cascade');
            $table->integer('step_order');
            $table->text('step_description')->nullable();
            $table->unique(['goal_id', 'step_order']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goal_action_steps');
    }
};