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
        Schema::create('goal_feedback', function (Blueprint $table) {
            $table->id('feedback_id');
            $table->foreignId('goal_id')->constrained('smart_goals', 'goal_id')->onDelete('cascade');
            $table->foreignId('staff_id')->nullable()->constrained('users', 'user_id');
            $table->text('feedback_content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goal_feedback');
    }
};