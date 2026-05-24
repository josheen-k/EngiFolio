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
        Schema::create('attainment_indicators', function (Blueprint $table) {
            $table->id('attainment_indicator_id');
            $table->foreignId('indicator_id')->constrained('competency_indicators', 'indicator_id')->cascadeOnDelete();
            $table->text('attainment_indicator')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attainment_indicators');
    }
};
