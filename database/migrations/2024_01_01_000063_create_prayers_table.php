<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prayers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('church_id')->unsigned();
            $table->foreign('church_id')->references('id')->on('church')->onDelete('cascade');
            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->foreign('category_id')->references('id')->on('prayer_categories')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->longText('text');
            $table->longText('original_text')->nullable();
            $table->enum('status', ['PENDING', 'ACTIVE', 'ANSWERED', 'ENDED', 'REJECTED', 'UNPUBLISHED'])->default('PENDING');
            $table->integer('approved_by')->unsigned()->nullable();
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->integer('rejected_by')->unsigned()->nullable();
            $table->foreign('rejected_by')->references('id')->on('users')->nullOnDelete();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('should_delete_at')->nullable();
            $table->integer('expiry_days')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('pinned_at')->nullable();
            $table->integer('pinned_by')->unsigned()->nullable();
            $table->foreign('pinned_by')->references('id')->on('users')->nullOnDelete();
            $table->text('answer_testimony')->nullable();
            $table->integer('answered_by')->unsigned()->nullable();
            $table->foreign('answered_by')->references('id')->on('users')->nullOnDelete();
            $table->timestamp('answered_at')->nullable();
            $table->unsignedInteger('member_count')->default(0);
            $table->unsignedInteger('guest_count')->default(0);
            $table->unsignedInteger('anonymous_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['church_id', 'status']);
            $table->index(['church_id', 'category_id', 'status']);
            $table->index(['church_id', 'expires_at', 'status']);
            $table->index(['church_id', 'pinned_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prayers');
    }
};
