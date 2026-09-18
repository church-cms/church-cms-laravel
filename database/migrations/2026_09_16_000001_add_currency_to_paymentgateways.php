<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paymentgateways', function (Blueprint $table) {
            if (!Schema::hasColumn('paymentgateways', 'currency')) {
                $table->string('currency')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('paymentgateways', function (Blueprint $table) {
            if (Schema::hasColumn('paymentgateways', 'currency')) {
                $table->dropColumn('currency');
            }
        });
    }
};
