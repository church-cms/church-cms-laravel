<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bible_books', function (Blueprint $table) {
            $table->bigIncrements('book_id');
            $table->string('english_book');
            $table->string('tamil_book')->nullable();
            $table->integer('chapter_count');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bible_books');
    }
};
