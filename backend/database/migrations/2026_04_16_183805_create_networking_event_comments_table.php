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
        Schema::create('networking_event_comments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('comment_text');
  
            $table->foreignId('event_id')->nullable()->constrained('networking_events', 'event_id')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('networking_event_comments');
    }
};