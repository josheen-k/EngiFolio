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
            $table->foreignId('role_id')->constrained('roles', 'role_id');
            $table->string('username', 32)->unique();
            $table->string('email', 254)->unique();
            $table->string('password_hash', 255);
            $table->string('account_status', 20)->default('active');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE users ADD CONSTRAINT check_account_status CHECK (account_status IN ('active', 'disabled'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
