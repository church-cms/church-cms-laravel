<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('church_id')->unsigned();
            $table->foreign('church_id')->references('id')->on('church');
            $table->enum('select_type', ['public', 'private', 'online'])->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('repeats')->nullable()->default(0);
            $table->integer('freq')->nullable()->default(0);
            $table->string('freq_term')->nullable();
            $table->string('month_type')->nullable();
            $table->json('days_of_week')->nullable();
            $table->unsignedSmallInteger('duration_minutes')->nullable();
            $table->text('location')->nullable();
            $table->enum('category', ['prayer', 'education', 'meeting', 'culturals', 'sermon'])->nullable();
            $table->longtext('organised_by')->nullable();
            $table->text('image')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->tinyInteger('allDay')->default(0);
            $table->text('url')->nullable();
            $table->boolean('publish_to_web')->default(true);
            $table->boolean('enable_gallery')->default(true);
            $table->boolean('enable_attendance')->default(false);
            $table->string('attendance_scope')->default('all');
            $table->unsignedBigInteger('attendance_group_id')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->integer('updated_by')->unsigned()->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
