<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlutterwaveService
{
    protected string $secretKey;
    protected string $baseUrl = 'https://api.flutterwave.com/v3';

    public function __construct(string $secretKey = '')
    {
        $this->secretKey = $secretKey ?: config('paymentgateway.flutterwave.secret_key');
    }

    public function verify(string $transactionId): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->secretKey,
        ])->get("{$this->baseUrl}/transactions/{$transactionId}/verify");

        $result = $response->json() ?? [];
        Log::info('Flutterwave verify', ['id' => $transactionId, 'status' => $response->status(), 'body' => $result]);

        return $result;
    }

    public function isSuccessful(array $result): bool
    {
        return ($result['status'] ?? '') === 'success'
            && ($result['data']['status'] ?? '') === 'successful';
    }

    public function getAmount(array $result): float
    {
        return (float) ($result['data']['amount'] ?? 0);
    }
}
