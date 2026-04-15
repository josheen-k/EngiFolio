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
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('cdl_id')->constrained('cdl_modules', 'cdl_id')->onDelete('cascade');
            $table->string('status', 25)->default('Not Started');
            $table->check("status IN ('Not Started', 'In Progress', 'Completed'')");
            $table->unsignedTinyInteger('progress_percentage')->default(0);
            $table->dateTime('last_accessed_at')->nullable();
            $table->dateTime('completed_at')->nullable();
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
