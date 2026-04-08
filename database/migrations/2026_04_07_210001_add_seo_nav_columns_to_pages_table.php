<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            // URL / nav
            $table->string('slug')->nullable()->after('page_name');
            $table->string('menu_text', 80)->nullable()->after('slug');
            $table->unsignedSmallInteger('menu_order')->default(0)->after('menu_text');

            // SEO
            $table->string('meta_title', 60)->nullable()->after('cover_image');
            $table->string('meta_description', 160)->nullable()->after('meta_title');
            $table->string('meta_keywords', 255)->nullable()->after('meta_description');
            $table->string('og_image')->nullable()->after('meta_keywords');
        });

        // Back-fill slugs from existing page_name values
        DB::table('pages')->get()->each(function ($page) {
            DB::table('pages')
                ->where('id', $page->id)
                ->update(['slug' => Str::slug($page->page_name) . '-' . $page->id]);
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn([
                'slug', 'menu_text', 'menu_order',
                'meta_title', 'meta_description', 'meta_keywords', 'og_image',
            ]);
        });
    }
};
