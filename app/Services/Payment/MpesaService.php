<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MpesaService
{
    protected string $consumerKey;
    protected string $consumerSecret;
    protected string $shortcode;
    protected string $passkey;
    protected string $baseUrl;

    public function __construct(string $consumerKey = '', string $consumerSecret = '', string $shortcode = '', string $passkey = '')
    {
        $this->consumerKey    = $consumerKey    ?: config('paymentgateway.mpesa.consumer_key');
        $this->consumerSecret = $consumerSecret ?: config('paymentgateway.mpesa.consumer_secret');
        $this->shortcode      = $shortcode      ?: config('paymentgateway.mpesa.shortcode');
        $this->passkey        = $passkey        ?: config('paymentgateway.mpesa.passkey');
        $this->baseUrl        = rtrim(config('paymentgateway.mpesa.base_url'), '/');
    }

    protected function getAccessToken(): string
    {
        $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->get($this->baseUrl . '/oauth/v1/generate?grant_type=client_credentials');

        $token = trim($response->json()['access_token'] ?? '');

        if (!$token) {
            Log::error('M-Pesa auth failed', ['status' => $response->status(), 'body' => $response->json()]);
            throw new \Exception('M-Pesa authentication failed: could not obtain access token.');
        }

        return $token;
    }

    public function stkPush(string $phone, float $amount, string $ref, string $callbackUrl): array
    {
        $timestamp   = now()->format('YmdHis');
        $password    = base64_encode($this->shortcode . $this->passkey . $timestamp);
        $accessToken = $this->getAccessToken();

        $response = Http::withToken($accessToken)
            ->post($this->baseUrl . '/mpesa/stkpush/v1/processrequest', [
                'BusinessShortCode' => $this->shortcode,
                'Password'          => $password,
                'Timestamp'         => $timestamp,
                'TransactionType'   => 'CustomerPayBillOnline',
                'Amount'            => (int) ceil($amount),
                'PartyA'            => $phone,
                'PartyB'            => $this->shortcode,
                'PhoneNumber'       => $phone,
                'CallBackURL'       => $callbackUrl,
                'AccountReference'  => 'ChurchDonation',
                'TransactionDesc'   => 'Church Donation ' . $ref,
            ]);

        $result = $response->json() ?? [];
        Log::info('M-Pesa STK response', ['status' => $response->status(), 'body' => $result]);

        return $result;
    }

    public function formatPhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '254' . substr($phone, 1);
        }
        if (!str_starts_with($phone, '254')) {
            $phone = '254' . $phone;
        }
        return $phone;
    }
}
