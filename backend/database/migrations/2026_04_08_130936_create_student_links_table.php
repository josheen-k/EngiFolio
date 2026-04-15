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
            $table->check("link_type IN ('linkedin', 'resume', 'cover_letter', 'github', 'portfolio', 'other')");
            $table->string('link_label', 100)->nullable();
            $table->string('link_url', 500);
            $table->unique(['profile_id', 'link_type', 'link_url']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_links');
    }
};
