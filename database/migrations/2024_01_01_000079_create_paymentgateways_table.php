<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paymentgateways', function (Blueprint $table) {
            $table->increments('id');
            $table->string('gatewayname')->unique();
            $table->string('displayname');
            $table->boolean('status');
            $table->string('currency')->nullable();
            $table->text('instructions')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paymentgateways');
    }
};
