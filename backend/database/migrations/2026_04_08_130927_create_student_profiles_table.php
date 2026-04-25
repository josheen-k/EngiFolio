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
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id('profile_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('first_name', 50)->nullable();
            $table->string('preferred_name', 50)->nullable();
            $table->string('last_name', 50);
            $table->string('degree_title', 40)->nullable();
            $table->string('specialisation', 60)->nullable();
            $table->text('personal_intro')->nullable();
            $table->string('profile_image_url', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};
