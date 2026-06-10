<?php
return [

      'mpesa' => [
            'consumer_key' => env('MPESA_CONSUMER_KEY'),
            'consumer_secret' => env('MPESA_CONSUMER_SECRET'),
            'shortcode' => env('MPESA_SHORTCODE'),
            'passkey' => env('MPESA_PASSKEY'),
            'base_url' => env('MPESA_BASE_URL'),
            'callback_url' => env('MPESA_CALLBACK_URL')
      ],
      'paypal' => [
            'merchant_email' => env('MERCHANT_EMAIL'),
            'mode' => env('MODE'),
      ],
      'paystack' => [
            'public_key' => env('PAYSTACK_PUBLIC_KEY'),
            'secret_key' => env('PAYSTACK_SECRET_KEY'),
            'currency' => env('PAYSTACK_CURRENCY'),
            'paystack_payment_url' => env('PAYSTACK_PAYMENT_URL'),
            'merchant_email' => env('MERCHANT_EMAIL')
      ],
      'flutterwave' => [
            'public_key' => env('FLUTTERWAVE_PUBLIC_KEY'),
            'secret_key' => env('FLUTTERWAVE_SECRET_KEY'),
            'currency' => env('FLUTTERWAVE_CURRENCY')
      ]
];
