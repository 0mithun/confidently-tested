<?php

namespace Tests\Feature\Http\Controllers;

use App\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
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

        //Mock it out


        $response = $this->post(route('order.store'), [
            'product_id'    =>  $product->id,
            'stripeToken'  =>  $this->faker->md5,
            'stripeEmail'   =>  $this->faker->safeEmail,

        ]);

        $response->assertRedirect('/users/edit');
    }
}
