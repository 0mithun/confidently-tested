<?php

namespace Tests\Feature;

use App\Mail\AccountCreated;
use App\User;
use App\Order;
use App\Product;
use Tests\TestCase;
use App\Mail\OrderConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateAccountTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_it_creates_an_order_and_user_accoungts()
    {
        $product = factory(Product::class)->create();

        $email = $this->faker->safeEmail;
        $transaction_id = $this->faker->md5;

        Mail::fake();

        $result = $this->artisan('make:account', [
            'email'     =>  $email,
            'product_id'    =>  $product->id,
            'transaction_id'    =>  $transaction_id,
        ])
        ->assertExitCode(0)
        ->run()
        ;

        $users = User::where('email', $email)->get();

        $this->assertSame(1, $users->count());

        $user = $users->first();

        $order = Order::query()
            ->where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->where('stripe_id', $transaction_id)
            ->where('total', $product->price)
        ->first();
        $this->assertNotNull($order);

        Mail::assertSent(OrderConfirmation::class, function($mail) use($email, $order) {
            return $mail->hasTo($email) && $mail->order->is($order);
        });

        Mail::assertSent(AccountCreated::class, function($mail) use($email) {
            return $mail->hasTo($email) && $mail->email === $email;
        });
    }
}
