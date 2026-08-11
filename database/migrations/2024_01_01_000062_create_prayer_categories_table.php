<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prayer_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('church_id')->unsigned();
            $table->foreign('church_id')->references('id')->on('church')->onDelete('cascade');
            $table->string('name', 50);
            $table->string('css_class', 50);
            $table->string('emoji', 10);
            $table->string('display_color', 7)->default('#6366F1');
            $table->string('gradient_start', 7)->default('#EEF2FF');
            $table->string('gradient_end', 7)->default('#E0E7FF');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('description', 500)->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['church_id', 'is_active']);
            $table->index(['church_id', 'sort_order']);
            $table->unique(['church_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prayer_categories');
    }
};
