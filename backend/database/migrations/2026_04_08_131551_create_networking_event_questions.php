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
        Schema::create('networking_event_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained('networking_events', 'event_id')->onDelete('cascade');
            $table->integer('question_order');
            $table->text('question_text');
            $table->unique(['event_id', 'question_order']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('networking_event_questions');
    }
};
