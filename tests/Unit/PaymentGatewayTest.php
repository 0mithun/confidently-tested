<?php

namespace Tests\Unit;

use App\Order;
use App\Services\PaymentGateway;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentGatewayTest extends TestCase
{

    public function testCharge()
    {
        $subject = new PaymentGateway();

        $token = $this->createTestToken();

        $order = new Order();

        $actual = $subject->charge($token, $order);

        $charge = $this->getStripeCharge($actual);

        $this->assertEquals($charge->total, $actual->total);
    }

    public function createTestToken()
    {
        $token = \Stripe\Token::create([
            'card' => [
                'number' => '4242424242424242',
                'exp_month' => 7,
                'exp_year' => now()->addYear()->format('Y'),
                'cvc' => '314'
            ]
        ], ['api_key' => config('services.stripe.secret')]);

        return $token->id;
    }
}
