<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prayer_participants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('church_id')->unsigned();
            $table->foreign('church_id')->references('id')->on('church')->onDelete('cascade');
            $table->bigInteger('prayer_id')->unsigned();
            $table->foreign('prayer_id')->references('id')->on('prayers')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->enum('participant_type', ['MEMBER', 'GUEST', 'ANONYMOUS'])->default('MEMBER');
            $table->string('anon_hash', 64)->nullable();
            $table->timestamps();
            $table->unique(['prayer_id', 'user_id']);
            $table->index(['prayer_id', 'anon_hash']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prayer_participants');
    }
};
