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
        Schema::create('competency_feedback', function (Blueprint $table) {
            $table->id('feedback_id');
            $table->foreignId('entry_id')->constrained('competency_entries', 'entry_id')->onDelete('cascade');
            $table->foreignId('staff_id')->nullable()->constrained('users', 'user_id');
            $table->text('feedback_content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competency_feedback');
    }
};
