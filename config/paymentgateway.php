<?php

return [

    'cash' => [],

    'bank' => [
        'account_name'   => env('BANK_ACCOUNT_NAME'),
        'account_number' => env('BANK_ACCOUNT_NUMBER'),
        'bank_name'      => env('BANK_NAME'),
        'bank_address'   => env('BANK_ADDRESS'),
        'branch_name'    => env('BANK_BRANCH_NAME'),
        'ifsc_code'      => env('BANK_IFSC_CODE'),
        'swift_code'     => env('BANK_SWIFT_CODE'),
    ],

    'cheque' => [
        'payee_name' => env('CHEQUE_PAYEE_NAME'),
    ],

    'gpay' => [
        'gpay_number' => env('GPAY_NUMBER'),
    ],

    'upi' => [
        'upi_id' => env('UPI_ID'),
    ],

    'paystack' => [
        'public_key'          => env('PAYSTACK_PUBLIC_KEY'),
        'secret_key'          => env('PAYSTACK_SECRET_KEY'),
        'currency'            => env('PAYSTACK_CURRENCY', 'NGN'),
        'paystack_payment_url' => env('PAYSTACK_PAYMENT_URL'),
        'merchant_email'      => env('MERCHANT_EMAIL'),
    ],

    'flutterwave' => [
        'public_key'     => env('FLUTTERWAVE_PUBLIC_KEY'),
        'secret_key'     => env('FLUTTERWAVE_SECRET_KEY'),
        'encryption_key' => env('FLUTTERWAVE_ENCRYPTION_KEY'),
        'currency'       => env('FLUTTERWAVE_CURRENCY', 'NGN'),
    ],

    'mpesa' => [
        'consumer_key'    => env('MPESA_CONSUMER_KEY'),
        'consumer_secret' => env('MPESA_CONSUMER_SECRET'),
        'shortcode'       => env('MPESA_SHORTCODE'),
        'passkey'         => env('MPESA_PASSKEY'),
        'base_url'        => env('MPESA_BASE_URL'),
        'callback_url'    => env('MPESA_CALLBACK_URL'),
    ],

    'gcash' => [
        'public_key' => env('GCASH_PUBLIC_KEY'),
        'secret_key' => env('GCASH_SECRET_KEY'),
        'currency'   => env('GCASH_CURRENCY', 'PHP'),
    ],

    'pix' => [
        'pix_key'  => env('PIX_KEY'),
        'currency' => env('PIX_CURRENCY', 'BRL'),
    ],

    'telebirr' => [
        'app_id'     => env('TELEBIRR_APP_ID'),
        'app_key'    => env('TELEBIRR_APP_KEY'),
        'public_key' => env('TELEBIRR_PUBLIC_KEY'),
        'short_code' => env('TELEBIRR_SHORT_CODE'),
        'currency'   => env('TELEBIRR_CURRENCY', 'ETB'),
    ],

    'stripe' => [
        'public_key'     => env('STRIPE_PUBLIC_KEY'),
        'secret_key'     => env('STRIPE_SECRET_KEY'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        'currency'       => env('STRIPE_CURRENCY', 'usd'),
    ],

];
