<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSendMailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('send_mail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('church_id')->unsigned();
            $table->foreign('church_id')->references('id')->on('church'); 
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');   
            $table->integer('entity_id')->nullable();
            $table->string('entity_name')->nullable();
            $table->text('from_address')->nullable();
            $table->enum('mode', ['mail','notification','sms'])->nullable();
            $table->text('from')->nullable();
            $table->text('to')->nullable();
            $table->text('subject')->nullable(); 
            $table->longtext('message')->nullable(); 
            $table->longtext('attachments')->nullable(); 
            $table->enum('status', ['queue','delivered','failed'])->nullable();
            $table->enum('type', ['mail','inbox','sent'])->nullable();
            $table->text('message_id')->nullable(); 
            $table->string('batch_id')->nullable()->unique();
            $table->timestamp('executed_at')->nullable();
            $table->boolean('is_executed')->default(0);
            $table->timestamp('fired_at')->nullable();
            $table->boolean('read_status')->default(0);
            $table->timestamp('read_at')->nullable();           
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('send_mail');
    }
}