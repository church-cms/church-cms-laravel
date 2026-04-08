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
        Schema::table('page_categories', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
            $table->string('icon', 50)->nullable()->after('slug');       // e.g. "🏛" or CSS class
            $table->text('description')->nullable()->after('icon');
            $table->unsignedSmallInteger('sort_order')->default(0)->after('description');
        });

        // Back-fill slug from existing name values
        DB::table('page_categories')->get()->each(function ($cat) {
            DB::table('page_categories')
                ->where('id', $cat->id)
                ->update(['slug' => Str::slug($cat->name)]);
        });

        // Now make slug unique + not nullable
        Schema::table('page_categories', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('page_categories', function (Blueprint $table) {
            $table->dropColumn(['slug', 'icon', 'description', 'sort_order']);
        });
    }
};
