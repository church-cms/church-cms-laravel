<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceLocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendancelocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date')->nullable();
            $table->enum('type',['sunday','weekly'])->nullable();
            $table->integer('meeting_maincat_id')->unsigned();
            $table->foreign('meeting_maincat_id')->references('id')->on('meeting_categories');
             $table->integer('meeting_subcat_id')->unsigned();
            $table->foreign('meeting_subcat_id')->references('id')->on('meeting_categories');
            $table->boolean('is_meeting1')->default('0');
            $table->boolean('is_meeting2')->default('0');
            $table->boolean('is_meeting3')->default('0');
            $table->boolean('is_meeting4')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendancelocks');
    }
}
