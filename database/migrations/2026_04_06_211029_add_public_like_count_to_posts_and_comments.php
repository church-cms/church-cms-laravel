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
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedInteger('public_like_count')->default(0)->after('status');
        });

        Schema::table('post_comments', function (Blueprint $table) {
            $table->unsignedInteger('public_like_count')->default(0)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('public_like_count');
        });

        Schema::table('post_comments', function (Blueprint $table) {
            $table->dropColumn('public_like_count');
        });
    }
};
