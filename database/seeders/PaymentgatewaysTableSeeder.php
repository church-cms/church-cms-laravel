<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentgatewaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('paymentgateways')->insert(
            [
                'gatewayname' => "cash",
                'displayname' => "Cash Account",
                'status'      => 1,
                'instructions' => 'Cash Payment',
                'created_at'  => date("Y-m-d H:i:s"),
                'updated_at'  => date("Y-m-d H:i:s"),
            ]
        );

        DB::table('paymentgateways')->insert(
            [
                'gatewayname' => "bank",
                'displayname' => "Bank Transfer",
                'status'      => 1,
                'instructions' => 'Bank Details',
                'created_at'  => date("Y-m-d H:i:s"),
                'updated_at'  => date("Y-m-d H:i:s"),
            ]
        );

        DB::table('paymentgateways')->insert(
            [
                'gatewayname' => "cheque",
                'displayname' => "Cheque",
                'status'      => 1,
                'instructions' => 'Check Payment Details',
                'created_at'  => date("Y-m-d H:i:s"),
                'updated_at'  => date("Y-m-d H:i:s"),
            ]
        );

        DB::table('paymentgateways')->insert(
            [
                'gatewayname' => "gpay",
                'displayname' => "Googlepay",
                'status'      => 1,
                'instructions' => 'Googlepay Payment Details',
                'created_at'  => date("Y-m-d H:i:s"),
                'updated_at'  => date("Y-m-d H:i:s"),
            ]
        );

        DB::table('paymentgateways')->insert(
            [
                'gatewayname' => "upi",
                'displayname' => "UPI",
                'status'      => 1,
                'instructions' => 'UPI Payment Payment',
                'created_at'  => date("Y-m-d H:i:s"),
                'updated_at'  => date("Y-m-d H:i:s"),
            ]
        );
    }
}
