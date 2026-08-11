<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_versions', function (Blueprint $table) {
            $table->id();
            $table->integer('page_id')->unsigned();
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->unsignedInteger('version_number');
            $table->longText('content')->nullable();
            $table->text('description')->nullable();
            $table->string('layout_template', 20)->nullable();
            $table->unsignedBigInteger('saved_by');
            $table->timestamps();
            $table->index(['page_id', 'version_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_versions');
    }
};
