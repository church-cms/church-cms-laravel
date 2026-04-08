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
        Schema::table('post_comments', function (Blueprint $table) {
            $table->string('guest_name')->nullable()->after('user_id');
            $table->string('guest_email')->nullable()->after('guest_name');
        });

        Schema::table('post_comments', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('post_comments', function (Blueprint $table) {
            $table->dropColumn(['guest_name', 'guest_email']);
            $table->unsignedInteger('user_id')->nullable(false)->change();
        });
    }
};
