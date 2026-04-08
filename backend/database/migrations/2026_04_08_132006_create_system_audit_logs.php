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
        Schema::create('system_audit_logs', function (Blueprint $table) {
            $table->id('log_id');
            $table->foreignId('admin_id')->constrained('users', 'user_id');
            $table->string('action_type', 100);
            $table->string('target_table', 100);
            $table->integer('target_row_id');
            $table->text('previous_value_snapshot');
            $table->text('new_value_snapshot');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_audit_logs');
    }
};
