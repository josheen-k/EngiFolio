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
        Schema::create('industry_contacts', function (Blueprint $table) {
            $table->id('contact_id');
            $table->foreignId('profile_id')->constrained('student_profiles', 'profile_id')->onDelete('cascade');
            $table->string('contact_name', 100);
            $table->string('company', 100)->nullable();
            $table->text('progress_notes')->nullable();
            $table->date('date_met')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('industry_contacts');
    }
};