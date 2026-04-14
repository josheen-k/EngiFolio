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
        Schema::create('mentor_student_mapping', function (Blueprint $table) {
            $table->id('mapping_id');
            $table->foreignId('staff_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users', 'user_id');
            $table->dateTime('assigned_at');
            $table->unique(['staff_id', 'student_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentor_student_mapping');
    }
};
