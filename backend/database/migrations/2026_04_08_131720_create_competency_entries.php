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
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('indicator_id')->constrained('competency_indicators', 'indicator_id');
            $table->string('experience_title', 255);
            $table->integer('associated_year');
            $table->text('experience_tasks');
            $table->text('key_learnings');
            $table->text('future_applications');
            $table->string('level', 25);
            $table->check("level IN ('Emerging', 'Developing', 'Proficient', 'Competent')");
            $table->string('status', 25)->default('Draft');
            $table->check("status IN ('Draft', 'Submitted', 'Reviewed')");
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();

            $table->index(['user_id', 'indicator_id', 'status'], 'entries_user_indicator_status_index');
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
