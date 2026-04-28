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
        Schema::create('student_links', function (Blueprint $table) {
            $table->id('link_id');
            $table->foreignId('profile_id')->constrained('student_profiles', 'profile_id')->onDelete('cascade');
            $table->string('link_type', 25);
            $table->string('link_label', 60)->nullable();
            $table->string('link_url', 500);
            $table->unique(['profile_id', 'link_type', 'link_url']);
            $table->timestamps();
        });

        DB::statement("ALTER TABLE student_links ADD CONSTRAINT check_link_type CHECK (link_type IN ('linkedin', 'resume', 'cover_letter', 'github', 'portfolio', 'other'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_links');
    }
};