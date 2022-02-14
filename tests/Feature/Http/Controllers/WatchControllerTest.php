<?php

namespace Tests\Feature\Http\Controllers;

use Mockery;
use App\User;
use App\Video;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WatchControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
     public function test_store_returns_a_204()
     {
         $this->withoutExceptionHandling();

         $user = factory(User::class)->create();
         $video = factory(Video::class)->create();

        //  $mock = Mockery::mock();
        //  $mock->expects('info')
        //     // ->once()
        //     ->with('video.watched', [$video->id]);

        // Log::swap($mock);

        $event = Event::fake();


         $this->actingAs($user);

         $response = $this->post(route('watches.store'), [
             'user_id'      =>  $user->id,
             'video_id'     =>  $video->id
         ]);


         $response->assertStatus(204);
         $event->assertDispatched('video.watched');

         $this->assertDatabaseHas('watches', [
             'user_id'  =>  $user->id,
             'video_id' =>  $video->id,
         ]);




     }
}
