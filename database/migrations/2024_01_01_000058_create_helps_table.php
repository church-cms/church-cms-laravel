<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('helps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('church_id')->unsigned();
            $table->foreign('church_id')->references('id')->on('church');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('contact_details')->nullable();
            $table->enum('status', ['pending', 'approve', 'reject', 'close'])->nullable();
            $table->string('comments')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->integer('closed_by')->unsigned()->nullable();
            $table->foreign('closed_by')->references('id')->on('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('helps');
    }
};
