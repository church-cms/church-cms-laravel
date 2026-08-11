<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->integer('entity_id');
            $table->string('entity_name');
            $table->longText('comments')->nullable();
            $table->string('attachment_file')->nullable();
            $table->boolean('status');
            $table->unsignedInteger('public_like_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_comments');
    }
};
