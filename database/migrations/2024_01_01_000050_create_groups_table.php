<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('church_id')->unsigned();
            $table->foreign('church_id')->references('id')->on('church');
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('group_category');
            $table->string('name')->nullable();
            $table->string('cover_image')->nullable();
            $table->text('description')->nullable();
            $table->enum('group_type', ['common_interests', 'everyone', 'married_couples', 'men', 'women', 'young_adults', 'youth'])->nullable();
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
