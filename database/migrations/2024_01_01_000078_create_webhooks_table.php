<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('webhooks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('verb')->default('POST');
            $table->string('name');
            $table->string('url');
            $table->unsignedBigInteger('mailinglist_id')->nullable();
            $table->foreign('mailinglist_id')->references('id')->on('mailinglists')->onDelete('cascade');
            $table->boolean('status')->default(1);
            $table->string('handshake_key')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webhooks');
    }
};
