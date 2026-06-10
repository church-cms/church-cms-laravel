<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Http;

config('paymentgateway.mpesa.consumer_key');
class PaystackService
{
    protected string $secretKey;
    protected string $publicKey;
    protected string $baseUrl = 'https://api.paystack.co';

    public function __construct(string $secretKey = '', string $publicKey = '')
    {
        $this->secretKey = $secretKey ?: config('paymentgateway.paystack.secret_key');
        $this->publicKey = $publicKey ?: config('paymentgateway.paystack.public_key');
    }

    public function verify(string $reference): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->secretKey,
        ])->get("{$this->baseUrl}/transaction/verify/{$reference}");

        return $response->json() ?? [];
    }

    public function isSuccessful(array $result): bool
    {
        return ($result['status'] ?? false) === true
            && ($result['data']['status'] ?? '') === 'success';
    }

    public function getAmount(array $result): float
    {
        // Paystack returns amount in kobo (smallest unit) — divide by 100
        return ($result['data']['amount'] ?? 0) / 100;
    }
}
