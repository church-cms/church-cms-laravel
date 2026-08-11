<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_attendees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('session_id');
            $table->foreign('session_id')->references('id')->on('event_attendance_sessions')->onDelete('cascade');
            $table->unsignedInteger('church_id');
            $table->foreign('church_id')->references('id')->on('church');
            $table->unsignedInteger('event_id');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('scanned_at')->nullable();
            $table->unsignedInteger('scanned_by')->nullable();
            $table->foreign('scanned_by')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['session_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_attendees');
    }
};
