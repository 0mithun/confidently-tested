<?php

namespace Tests\Feature\Http\Controllers;

use Mockery;
use App\Order;
use App\Product;
use Tests\TestCase;
use Stripe\Error\Card;
use App\Services\PaymentGateway;
use Illuminate\Foundation\Testing\WithFaker;
use App\Exceptions\PaymentGateChargeException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /**
     * @test
     */
    public function order_store_test()
    {
        $this->withoutExceptionHandling();

        $product = factory(Product::class)->create();

        //Mock Paymentgateway

        $token = $this->faker->md5;

        $paymentGateway = $this->mock(PaymentGateway::class);
        $paymentGateway->shouldReceive('charge')
            ->with($token, Mockery::type(Order::class))
            ->andReturn('charge-id')
            ;

        $response = $this->post(route('order.store'), [
            'product_id'    =>  $product->id,
            'stripeToken'  =>  $token,
            'stripeEmail'   =>  $this->faker->safeEmail,

        ]);

        $response->assertRedirect('/users/edit');

        $this->markTestIncomplete();
    }


    /**
     * @test
     */

     public function store_return_errors_view_when_charge_failed()
     {
        // $this->withoutExceptionHandling();

        $product = factory(Product::class)->create();

        //Mock Paymentgateway

        $token = $this->faker->md5;
        $exception = new PaymentGateChargeException('sad path dummy exception', ['error'=>['data'=>'passed to view']],);

        $paymentGateway = $this->mock(PaymentGateway::class);
        $paymentGateway->shouldReceive('charge')
            ->with($token, Mockery::type(Order::class))
            ->andThrows($exception)
            ;

        $response = $this->post(route('order.store'), [
            'product_id'    =>  $product->id,
            'stripeToken'  =>  $token,
            'stripeEmail'   =>  $this->faker->safeEmail,

        ]);


        $response->assertOk();
        $response->assertViewIs('errors.generic');
        $response->assertViewHas('template', 'partials.errors.charge_failed');
        $response->assertViewHas('data',  ['data'=> 'passed to view']);
     }
}
