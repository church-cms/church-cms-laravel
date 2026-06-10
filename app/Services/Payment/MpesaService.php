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

        //dd($this->consumerKey . "hhh" . $this->consumerSecret . 'Base' . $this->baseUrl);

        $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->get($this->baseUrl . "/oauth/v1/generate?grant_type=client_credentials");




        $token = $response->json()['access_token'] ?? '';

        //dd($token);

        $data = ['token' => $token, 'status' => $response->status(), 'body' => $response->json()];

        // dd($data);

        if (!$token) {
            Log::error('M-Pesa auth failed', ['status' => $response->status(), 'body' => $response->json()]);
        }

        //dd($token);

        return $token;
    }

    public function stkPush(string $phone, float $amount, string $ref, string $callbackUrl): array
    {
        $timestamp = now()->format('YmdHis');
        //$password  = base64_encode($this->shortcode . $this->passkey . $timestamp);

        $password  = base64_encode('174379' . 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919' . $timestamp);

        $accessToken = $this->getAccessToken();

        // dd([
        //     'phone' => $phone,
        //     'callbackUrl' => $callbackUrl,
        //     'timestamp' => $timestamp,
        //     'password' => $password,
        // ]);


        // dd([
        //     'consumer_key' => substr($this->consumerKey, 0, 5) . '...',
        //     'shortcode'    => $this->shortcode,
        //     'passkey_len'  => strlen($this->passkey),
        //     'token'        => $accessToken,
        // ]);

        //dd($this->baseUrl);

        $response = Http::withHeaders(['Authorization' => 'Bearer ' . $accessToken])
            ->post($this->baseUrl . "/mpesa/stkpush/v1/processrequest", [
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

        // $response = Http::withToken($accessToken)
        //     ->post('https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest', [
        //         'BusinessShortCode' => '174379',
        //         'Password' => $password,
        //         'Timestamp' => $timestamp,
        //         'TransactionType' => 'CustomerPayBillOnline',
        //         'Amount' => 1,
        //         'PartyA' => '254708374149',
        //         'PartyB' => '174379',
        //         'PhoneNumber' => '254708374149',
        //         'CallBackURL' => $callbackUrl,
        //         'AccountReference' => 'Test',
        //         'TransactionDesc' => 'Test Payment',
        //     ]);


        dd([
            'status' => $response->status(),
            'body'   => $response->body(),
            'callbackUrl' => $callbackUrl,
        ]);

        dd($response->json());



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
