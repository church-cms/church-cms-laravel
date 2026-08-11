<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('funds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('church_id')->unsigned();
            $table->foreign('church_id')->references('id')->on('church');
            $table->integer('payaccount_id')->unsigned()->nullable();
            $table->foreign('payaccount_id')->references('id')->on('payaccounts');
            $table->integer('authorised_by')->unsigned()->nullable();
            $table->foreign('authorised_by')->references('id')->on('users');
            $table->dateTime('authorised_at')->nullable();
            $table->enum('membership', ['guest', 'member']);
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->longText('data')->nullable();
            $table->double('amount');
            $table->enum('method', ['bank', 'card', 'cash', 'cheque', 'demanddraft'])->nullable();
            $table->longText('payment_details')->nullable();
            $table->enum('status', ['request', 'pending', 'deposited', 'cancel']);
            $table->string('uuid')->nullable();
            $table->longText('comments')->nullable();
            $table->text('attachment')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('funds');
    }
};
