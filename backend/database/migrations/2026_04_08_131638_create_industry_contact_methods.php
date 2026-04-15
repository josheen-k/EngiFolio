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
            $table->foreignId('contact_id')->constrained('industry_contacts', 'contact_id')->onDelete('cascade');
            $table->string('method_type', 25);
            $table->string('method_value', 500);
            $table->timestamps();
        });

        DB::statement("ALTER TABLE industry_contact_methods ADD CONSTRAINT check_contact_method CHECK (method_type IN ('phone', 'email', 'linkedin', 'website', 'other'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('industry_contact_methods');
    }
};
