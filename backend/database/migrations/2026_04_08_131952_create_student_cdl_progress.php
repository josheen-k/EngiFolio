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
        Schema::create('student_cdl_progress', function (Blueprint $table) {
            $table->id('progress_id');
            $table->foreignId('user_id')->constrained('users', 'user_id');
            $table->foreignId('cdl_id')->constrained('cdl_modules', 'cdl_id');
            $table->enum('status', ['Not Started', 'In Progress', 'Completed']);
            $table->unsignedTinyInteger('progress_percentage')->default(0);
            $table->dateTime('last_accessed_at');
            $table->dateTime('completed_at');
            $table->unique(['user_id', 'cdl_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_cdl_progress');
    }
};
