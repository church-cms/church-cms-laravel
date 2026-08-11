<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('church_id')->unsigned();
            $table->foreign('church_id')->references('id')->on('church');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->boolean('status')->default('0');
            $table->unsignedBigInteger('mailinglist_id')->nullable();
            $table->foreign('mailinglist_id')->references('id')->on('mailinglists')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign');
    }
};
