<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('church_id')->unsigned();
            $table->foreign('church_id')->references('id')->on('church');
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('page_categories');
            $table->string('page_name');
            $table->string('slug')->nullable();
            $table->string('menu_text', 80)->nullable();
            $table->unsignedSmallInteger('menu_order')->default(0);
            $table->longText('description');
            $table->longText('content')->nullable();
            $table->string('layout_template', 20)->default('left-sidebar');
            $table->string('cover_image')->nullable();
            $table->string('meta_title', 60)->nullable();
            $table->string('meta_description', 160)->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->string('og_image')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->boolean('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
