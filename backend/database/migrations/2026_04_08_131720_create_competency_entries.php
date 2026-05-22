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
        Schema::create('competency_entries', function (Blueprint $table) {
            $table->id('entry_id');
            $table->foreignId('profile_id')->constrained('student_profiles', 'profile_id')->onDelete('cascade');
            $table->foreignId('indicator_id')->constrained('competency_indicators', 'indicator_id');
            $table->string('experience_title', 50);
            $table->integer('associated_year');
            $table->text('experience_tasks', 500);
            $table->text('key_learnings', 500)->nullable();
            $table->text('future_applications', 500)->nullable();
            $table->foreignId('entry_level_id')->constrained('competency_entry_levels', 'entry_level_id');
            $table->foreignId('entry_status_id')->constrained('competency_entry_statuses', 'entry_status_id');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();

            $table->index(['profile_id', 'indicator_id', 'entry_status_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competency_entries');
    }
};