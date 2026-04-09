<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageVersionsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('page_versions')) {
            return;
        }

        Schema::create('page_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('page_id');
            $table->unsignedInteger('version_number');
            $table->longText('content')->nullable();
            $table->text('description')->nullable();
            $table->string('layout_template', 20)->nullable();
            $table->unsignedBigInteger('saved_by');
            $table->timestamps();

            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->index(['page_id', 'version_number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('page_versions');
    }
}
