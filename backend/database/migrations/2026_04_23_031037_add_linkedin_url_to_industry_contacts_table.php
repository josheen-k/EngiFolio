<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('industry_contacts', 'linkedin_url')) {
            Schema::table('industry_contacts', function (Blueprint $table) {
                $table->string('linkedin_url', 500)->nullable()->after('date_met');
            });
        }
    }
    public function down(): void
    {
        Schema::table('industry_contacts', function (Blueprint $table) {
            $table->dropColumn('linkedin_url');
        });
    }
};