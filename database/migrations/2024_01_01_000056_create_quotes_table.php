<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('church_id')->unsigned();
            $table->foreign('church_id')->references('id')->on('church');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('image')->nullable();
            $table->longtext('text')->nullable();
            $table->longtext('tamil_quotes')->nullable();
            $table->longtext('english_quotes')->nullable();
            $table->timestamp('publish_on')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->boolean('status')->default('0');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
