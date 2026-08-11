<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mail_queues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('email_id');
            $table->unsignedBigInteger('subscriber_id');
            $table->unsignedBigInteger('mailinglist_id');
            $table->unsignedBigInteger('campaign_id');
            $table->string('subject');
            $table->string('from_email');
            $table->string('from_name');
            $table->string('reply_to_email');
            $table->string('to_mail');
            $table->text('content');
            $table->datetime('scheduled_at');
            $table->datetime('fired_at')->nullable();
            $table->datetime('failed_at')->nullable();
            $table->text('exception')->nullable();
            $table->text('smtp')->nullable();
            $table->boolean('is_read')->default(0);
            $table->unsignedBigInteger('clicks')->default(0);
            $table->enum('status', ['sent', 'delivered', 'bounce', 'spam'])->nullable();
            $table->datetime('rule_checked_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mail_queues');
    }
};
