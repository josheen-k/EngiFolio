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
        Schema::create('competency_groups', function (Blueprint $table) {
            $table->id('group_id');
            $table->string('display_id', 20)->unique();
            $table->string('group_name', 255);
            $table->text('description')->nullable();
            $table->date('discontinued_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competency_groups');
    }
};