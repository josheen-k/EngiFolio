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
        Schema::create('student_actions', function (Blueprint $table) {
            $table->id('student_action_id');
            $table->foreignId('student_profile_id')->constrained('student_profiles', 'profile_id')->onDelete('cascade');
            $table->string('action', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_actions');
    }
};
