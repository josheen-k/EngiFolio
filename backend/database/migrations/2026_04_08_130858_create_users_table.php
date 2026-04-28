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
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('username', 9)->unique();
            $table->string('email', 254)->unique();
            $table->string('first_name', 50)->nullable();
            $table->string('last_name', 50);
            $table->string('password_hash', 255);
            $table->foreignId('account_status_id')->constrained('account_statuses', 'account_status_id')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
