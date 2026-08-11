<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_attendance_sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('church_id');
            $table->foreign('church_id')->references('id')->on('church');
            $table->unsignedInteger('event_id');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->date('attendance_date');
            $table->unsignedInteger('opened_by');
            $table->foreign('opened_by')->references('id')->on('users');
            $table->timestamp('locked_at')->nullable();
            $table->unsignedInteger('locked_by')->nullable();
            $table->foreign('locked_by')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['event_id', 'attendance_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_attendance_sessions');
    }
};
