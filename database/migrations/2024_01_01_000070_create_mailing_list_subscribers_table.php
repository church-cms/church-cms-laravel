<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mailing_list_subscribers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('mailing_list_id');
            $table->unsignedBigInteger('subscribers_id');
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mailing_list_subscribers');
    }
};
