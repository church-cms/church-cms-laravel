<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
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

    public function down(): void
    {
        Schema::dropIfExists('bible_verses');
    }
};
