<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Corrects the long-standing typo 'patner' → 'partner' in userprofiles.relation
 * and userprofiles.family columns.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('userprofiles')
            ->where('relation', 'patner')
            ->update(['relation' => 'partner']);

        DB::table('userprofiles')
            ->where('family', 'patner')
            ->update(['family' => 'partner']);
    }

    public function down(): void
    {
        DB::table('userprofiles')
            ->where('relation', 'partner')
            ->update(['relation' => 'patner']);

        DB::table('userprofiles')
            ->where('family', 'partner')
            ->update(['family' => 'patner']);
    }
};
