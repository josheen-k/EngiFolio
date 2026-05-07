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
        //
        Schema::table('networking_event_comments', function(Blueprint $table) {
            $table->string('comment_type', 10)->nullable()->after('comment_text');
            $table->string('link_url')->nullable()->after('comment_type');
            $table->string('file_path')->nullable()->after('link_url');
            $table->string('file_name')->nullable()->after('file_path');

            $table->text('comment_text')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('networking_event_comments', function(Blueprint $table) {
            $table->dropColumn(['comment_type', 'link_url', 'file_path', 'file_name']);

            $table->text('comment_text')->nullable(false)->change();
        });
    }
};
