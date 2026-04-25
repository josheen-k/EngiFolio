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
            $table->string('experience_title', 255);
            $table->integer('associated_year');
            $table->text('experience_tasks');
            $table->text('key_learnings')->nullable();
            $table->text('future_applications')->nullable();
            $table->string('level', 15);
            $table->string('status', 15)->default('Draft');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();

            $table->index(['profile_id', 'indicator_id', 'status'], 'entries_user_indicator_status_index');
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
