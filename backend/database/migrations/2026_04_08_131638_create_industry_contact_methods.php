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
        Schema::create('industry_contact_methods', function (Blueprint $table) {
            $table->id('method_id');
            $table->foreignId('contact_id')->constrained('industry_contacts', 'contact_id');
            $table->enum('contact_method', ['phone', 'email', 'linkedin', 'website', 'other'])->default('other');
            $table->string('method_value', 500);
            $table->unique(['contact_id', 'contact_method']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('industry_contact_methods');
    }
};
