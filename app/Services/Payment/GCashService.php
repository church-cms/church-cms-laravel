<?php

namespace App\Services\Payment;

use Exception;
use Illuminate\Support\Facades\Http;

class GCashService
{
    protected string $secretKey;
    protected string $currency;
    private const BASE = 'https://api.paymongo.com/v1';

    public function __construct(string $secretKey = '', string $currency = 'PHP')
    {


        $this->secretKey = $secretKey ?: config('paymentgateway.gcash.secret_key', '');
        $this->currency  = strtoupper($currency ?: 'PHP');
    }

    public function createSource(float $amount, string $successUrl, string $failedUrl, string $description = 'Church Donation'): array
    {
        $response = Http::withBasicAuth($this->secretKey, '')
            ->post(self::BASE . '/sources', [
                'data' => [
                    'attributes' => [
                        'amount'      => (int) round($amount * 100),
                        'currency'    => $this->currency,
                        'type'        => 'gcash',
                        'description' => $description,
                        'redirect'    => [
                            'success' => $successUrl,
                            'failed'  => $failedUrl,
                        ],
                    ],
                ],
            ]);

        if (!$response->successful()) {
            throw new Exception('GCash source creation failed: ' . $response->body());
        }

        $data = $response->json()['data'];
        return [
            'source_id'    => $data['id'],
            'checkout_url' => $data['attributes']['redirect']['checkout_url'],
            'status'       => $data['attributes']['status'],
        ];
    }

    public function getSource(string $sourceId): array
    {
        $response = Http::withBasicAuth($this->secretKey, '')
            ->get(self::BASE . "/sources/{$sourceId}");

        if (!$response->successful()) {
            throw new Exception('GCash source retrieval failed: ' . $response->body());
        }

        return $response->json()['data'] ?? [];
    }

    public function createPayment(string $sourceId, float $amount, string $description = 'Church Donation'): array
    {
        $response = Http::withBasicAuth($this->secretKey, '')
            ->post(self::BASE . '/payments', [
                'data' => [
                    'attributes' => [
                        'amount'      => (int) round($amount * 100),
                        'currency'    => $this->currency,
                        'description' => $description,
                        'source'      => [
                            'id'   => $sourceId,
                            'type' => 'source',
                        ],
                    ],
                ],
            ]);

        if (!$response->successful()) {
            throw new Exception('GCash payment capture failed: ' . $response->body());
        }

        return $response->json()['data'] ?? [];
    }

    public function isChargeable(array $source): bool
    {
        return ($source['attributes']['status'] ?? '') === 'chargeable';
    }
}
