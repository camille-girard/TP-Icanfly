<?php

namespace App\Service;

use Stripe\StripeClient;

class StripeService
{
    private StripeClient $stripe;

    public function __construct()
    {
        // Initialise Stripe avec la clé secrète
        $this->stripe = new StripeClient($_ENV['STRIPE_SECRET_KEY']);
    }

    public function createCheckoutSession(string $currency, string $productName, int $amount, string $successUrl, string $cancelUrl): \Stripe\Checkout\Session
    {
        return $this->stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => $productName,
                    ],
                    'unit_amount' => $amount,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
        ]);
    }
}
