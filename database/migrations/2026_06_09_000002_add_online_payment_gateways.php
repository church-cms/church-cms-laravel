<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddOnlinePaymentGateways extends Migration
{
    public function up()
    {
        $now = date('Y-m-d H:i:s');

        $gateways = [
            ['gatewayname' => 'paystack',    'displayname' => 'Paystack',    'instructions' => 'Pay securely via Paystack (card, bank, USSD)',       'currency' => 'NGN'],
            ['gatewayname' => 'flutterwave', 'displayname' => 'Flutterwave', 'instructions' => 'Pay via Flutterwave (card, mobile money, bank)',      'currency' => 'NGN'],
            ['gatewayname' => 'mpesa',       'displayname' => 'M-Pesa',      'instructions' => 'Pay via M-Pesa Lipa na M-Pesa (Kenya)',               'currency' => 'KES'],
            ['gatewayname' => 'gcash',       'displayname' => 'GCash',       'instructions' => 'Pay via GCash (Philippines)',                          'currency' => 'PHP'],
            ['gatewayname' => 'stripe',      'displayname' => 'Stripe',      'instructions' => 'Pay via Stripe',                                       'currency' => 'USD'],
        ];

        // ['gatewayname' => 'pix',         'displayname' => 'PIX',         'instructions' => 'Pay via PIX (Brazil)',                                 'currency' => 'BRL'],
        //     ['gatewayname' => 'telebirr',    'displayname' => 'Telebirr',    'instructions' => 'Pay via Telebirr (Ethiopia)',                          'currency' => 'ETB'],

        foreach ($gateways as $gw) {
            $exists = DB::table('paymentgateways')->where('gatewayname', $gw['gatewayname'])->exists();
            if (!$exists) {
                DB::table('paymentgateways')->insert(array_merge($gw, [
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }
    }

    public function down()
    {
        DB::table('paymentgateways')->whereIn('gatewayname', [
            'paystack',
            'flutterwave',
            'mpesa',
            'gcash',
            'pix',
            'telebirr',
        ])->delete();
    }
}
