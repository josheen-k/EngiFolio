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
        Schema::table('smart_goals', function (Blueprint $table) {
            $table->text('timeline')->nullable()->change();
            $table->text('progress_notes')->nullable()->change();
            $table->text('learnings')->nullable()->change();
            $table->date('start_date')->nullable()->change();
            $table->date('end_date')->nullable()->change();
            $table->date('completion_date')->nullable()->change();
            $table->text('completion_notes')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('smart_goals', function (Blueprint $table) {
            $table->text('timeline')->nullable(false)->change();
            $table->text('progress_notes')->nullable(false)->change();
            $table->text('learnings')->nullable(false)->change();
            $table->date('start_date')->nullable(false)->change();
            $table->date('end_date')->nullable(false)->change();
            $table->date('completion_date')->nullable(false)->change();
            $table->text('completion_notes')->nullable(false)->change();
        });
    }
};