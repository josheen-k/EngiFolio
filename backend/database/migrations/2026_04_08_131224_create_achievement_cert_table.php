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
        Schema::create('achievement_cert', function (Blueprint $table) {
            $table->id('achievement_cert_id');
            $table->foreignId('user_id')->constrained('users', 'user_id');
            $table->string('title', 255);
            $table->text('body');
            $table->string('file_path', 500);
            $table->date('issued_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievement_cert');
    }
};
