<?php

/**
 * This table maps contacts to networking events
 */

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
        Schema::create('networking_event_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')
                ->constrained('networking_events', 'event_id')
                ->cascadeOnDelete();
            $table->foreignId('contact_id')
                ->constrained('industry_contacts', 'contact_id')
                ->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['event_id', 'contact_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('networking_event_contacts');
    }
};
