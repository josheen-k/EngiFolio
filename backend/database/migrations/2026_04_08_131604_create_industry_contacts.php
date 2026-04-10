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
            $table->foreignId('user_id')->constrained('users', 'user_id');
            $table->string('contact_name', 255);
            $table->string('company', 255);
            $table->text('progress_notes');
            $table->date('date_met');
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
