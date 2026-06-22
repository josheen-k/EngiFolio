<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('student_profiles', 'year_started')) {
            return;
        }

        Schema::table('student_profiles', function (Blueprint $table) {
            $table->year('year_started')->nullable()->after('profile_image_url');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('student_profiles', 'year_started')) {
            return;
        }

        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumn('year_started');
        });
    }
};
