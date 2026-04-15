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
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->enum('link_type',['linkedin','resume','cover_letter','github', 'portfolio','other']);
            $table->string('link_label', 100);
            $table->string('link_url', 500);
            $table->integer('display_order')->default(1);
            $table->unique(['user_id', 'link_type', 'link_url']);
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
