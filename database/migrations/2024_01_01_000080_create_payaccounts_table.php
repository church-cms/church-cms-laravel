<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payaccounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('church_id')->unsigned();
            $table->foreign('church_id')->references('id')->on('church');
            $table->integer('paymentgateway_id')->unsigned();
            $table->foreign('paymentgateway_id')->references('id')->on('paymentgateways');
            $table->boolean('status')->default(1);
            $table->text('attachment')->nullable();
            $table->text('comments')->nullable();
            $table->string('param1')->nullable();
            $table->string('param2')->nullable();
            $table->string('param3')->nullable();
            $table->string('param4')->nullable();
            $table->string('param5')->nullable();
            $table->string('param6')->nullable();
            $table->string('param7')->nullable();
            $table->string('param8')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payaccounts');
    }
};
