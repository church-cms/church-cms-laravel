<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('church_id')->unsigned();
            $table->foreign('church_id')->references('id')->on('church');
            $table->integer('category_id')->unsigned()->nullable();
            $table->foreign('category_id')->references('id')->on('post_categories');
            $table->integer('entity_id')->nullable();
            $table->string('entity_name')->nullable();
            $table->string('title')->nullable();
            $table->longText('description');
            $table->string('attachment_file')->nullable();
            $table->timestamp('post_created_at')->nullable();
            $table->boolean('is_posted')->default(0);
            $table->timestamp('posted_at')->nullable();
            $table->integer('tag')->nullable();
            $table->enum('status', ['drafted', 'pending', 'posted', 'cancelled'])->nullable();
            $table->unsignedInteger('public_like_count')->default(0);
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
