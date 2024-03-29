<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginControllerTest extends TestCase
{

    use RefreshDatabase;
    /**
     * @test
     *
     * @return void
     */
    public function login_redirects_to_dashboard()
    {
        $user = factory(User::class)->create();
        $response = $this->post('/login', [
            'email'     =>  $user->email,
            'password'  =>  'password',
        ]);

        $response->assertRedirect('/dashboard');

        //assert the user was logged in
        $this->assertAuthenticatedAs($user);
    }
}
