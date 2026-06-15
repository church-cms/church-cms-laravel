<?php

namespace App\Services\Payment;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Exception;

class StripeService
{
    protected string $secretKey;
    protected string $publicKey;
    protected string $currency;

    public function __construct(string $secretKey = '', string $publicKey = '', string $currency = 'usd')
    {
        $this->secretKey = $secretKey ?: config('paymentgateway.stripe.secret_key', '');
        $this->publicKey = $publicKey ?: config('paymentgateway.stripe.public_key', '');
        $this->currency  = strtolower($currency ?: config('paymentgateway.stripe.currency', 'usd'));

        Stripe::setApiKey($this->secretKey);
    }

    /**
     * Create a PaymentIntent and return client_secret + id.
     */
    public function createIntent(float $amount, string $description = 'Church Donation'): array
    {
        $intent = PaymentIntent::create([
            'amount'      => (int) round($amount * 100), // cents
            'currency'    => $this->currency,
            'description' => $description,
            'automatic_payment_methods' => ['enabled' => true],
        ]);

        return [
            'client_secret'      => $intent->client_secret,
            'payment_intent_id'  => $intent->id,
        ];
    }

    /**
     * Retrieve and confirm a PaymentIntent, returning whether it succeeded.
     */
    public function verify(string $paymentIntentId): array
    {
        return PaymentIntent::retrieve($paymentIntentId)->toArray();
    }

    public function isSuccessful(array $result): bool
    {
        return ($result['status'] ?? '') === 'succeeded';
    }
}
