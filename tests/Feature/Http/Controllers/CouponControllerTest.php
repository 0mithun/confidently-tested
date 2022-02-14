<?php

namespace Tests\Feature\Http\Controllers;

use App\Coupon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CouponControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     */
    public function it_store_coupon_and_redirect()
    {
        $coupon = factory(Coupon::class)->create();

        $response = $this->get('/promotions/'.$coupon->code);

        $response->assertRedirect('/#buy-now');
        $response->assertSessionHas('coupon_id', $coupon->id);
    }



    /**
     * Undocumented function
     * @test
     */
    public function it_does_not_store_coupon_for_invalid_code()
    {
        $response = $this->get('/promotions/invalid-code');

        $response->assertCookieMissing('coupon_id');
    }

     /**
     * Undocumented function
     * @test
     */
    public function it_does_not_store_an_expire_coupon()
    {
        $coupon = factory(Coupon::class)->create([
            'expired_at' => now()
        ]);

        $response = $this->get('/promotions/'.$coupon->id);

        $response->assertRedirect('/#buy-now');

        $response->assertCookieMissing('coupon_id');
    }


     /**
     * Undocumented function
     * @test
     */
    public function it_does_not_store_a_previously_expire_coupon()
    {
        $this->markTestIncomplete();
        $coupon = factory(Coupon::class)->create();

        $response = $this->get('/promotions/'.$coupon->id);
        //test comment

        $response->assertRedirect('/#buy-now');

        // $response->assertCookieMissing('coupon_id');
    }
}
