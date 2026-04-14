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
        Schema::create('competency_evidence', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entry_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('evidence_type', 25);
            $table->check("evidence_type IN ('url', 'file')");
            $table->string('evidence_value', 500);
            $table->unique(['entry_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competency_evidence');
    }
};
