<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBibleVersesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bible_verses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('english_verse');
            $table->text('tamil_verse')->nullable();
            $table->integer('book_id');
            $table->integer('chapter_id');
            $table->integer('verse_id'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bible_verses');
    }
}
