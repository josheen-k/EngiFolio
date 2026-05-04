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
        Schema::create('competency_evidences', function (Blueprint $table) {
            $table->id('evidence_id');
            $table->foreignId('entry_id')->constrained('competency_entries', 'entry_id')->onDelete('cascade');
            $table->foreignId('evidence_type_id')->constrained('competency_evidence_types', 'evidence_type_id')->onDelete('restrict');
            $table->string('evidence_value', 500);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competency_evidences');
    }
};
