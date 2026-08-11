<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('church_id')->unsigned();
            $table->foreign('church_id')->references('id')->on('church');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('entity_id')->nullable();
            $table->string('entity_name')->nullable();
            $table->text('title')->nullable();
            $table->enum('category', ['prayer', 'education', 'meeting', 'culturals'])->nullable();
            $table->dateTime('date')->nullable();
            $table->boolean('is_present')->default('0');
            $table->date('present_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
