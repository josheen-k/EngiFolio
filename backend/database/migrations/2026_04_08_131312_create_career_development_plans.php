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
        Schema::create('career_development_plans', function (Blueprint $table) {
            $table->id('plan_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->integer('plan_year');
            $table->text('professional_interests');
            $table->text('employers_of_interest');
            $table->text('networking_plan');
            $table->text('personal_values');
            $table->text('extracurriculars');
            $table->text('development_focus');
            $table->unique(['user_id', 'plan_year']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_development_plans');
    }
};
