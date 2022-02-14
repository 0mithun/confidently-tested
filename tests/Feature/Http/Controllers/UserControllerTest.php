<?php

namespace Tests\Feature\Http\Controllers;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @test
     */
     public function update_saves_data_and_redirect_to_dashboard()
     {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $this->actingAs($user);

        $name = $this->faker->name();
        $password = $this->faker->password(8);
        $response = $this->put('/users', [
            'name'      =>  $name,
            'password'  =>  $password,
            'password_confirmation' => $password,
        ]);

        $response->assertRedirect('/dashboard');

        $user->refresh();

        $this->assertEquals($name, $user->name);
        $this->assertTrue(Hash::check($password, $user->password));
     }



     /**
      * @test
      */

      public function update_failes_for_invalid_name()
      {
        $user = factory(User::class)->create();

        $this->actingAs($user);
        $this->from(route('user.edit'));

        $password = $this->faker->password(8);
        $response = $this->put('/users', [
            'name'      =>  null,
            'password'  =>  $password,
            'password_confirmation' => $password,
        ]);

        $response->assertRedirect(route('user.edit'));

        $response->assertSessionHasErrors('name');
      }


       /**
        * @test
        */

    public function update_user_validation()
    {
        //
    }
}
