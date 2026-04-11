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
        Schema::create('competency_indicators', function (Blueprint $table) {
            $table->id('indicator_id');
            $table->foreignId('group_id')->constrained('competency_groups', 'group_id');
            $table->string('display_id', 20);
            $table->text('description');
            $table->string('indicator_link', 500);
            $table->date('discontinued_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competency_indicators');
    }
};
