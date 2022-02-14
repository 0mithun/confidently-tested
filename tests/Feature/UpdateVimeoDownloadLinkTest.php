<?php

namespace Tests\Feature;

use App\Video;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Vimeo\Laravel\Facades\Vimeo;

class UpdateVimeoDownloadLinkTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $videos = factory(Video::class, 2)->create();
        $viemo = Mockery::mock();

        $viemo->shouldReceive('request')
            ->with('/videos/'.$videos->first()->vimeo_id)
            ->andReturn($this->createVideoResponse($videos->first()))
        ;

        $viemo->shouldReceive('request')
            ->with('/videos/'.$videos->last()->vimeo_id)
            ->andReturn($this->createVideoResponse($videos->last()))
        ;

        Vimeo::swap($viemo);

        $this->artisan('videos:update')
            ->assertExitCode(0)
            ->run()
        ;

        $videos->each(function($video){
            $video->refresh();
            $this->assertSame('hd-download-link-'.$video->vimeo_id, $video->vimeo_hd_download_url);
            $this->assertSame('sd-download-link-'.$video->vimeo_id, $video->vimeo_sd_download_url);
        });

    }

    private function createVideoResponse($video)
    {
        return [
            'body'  =>  [
                'download'  =>  [
                    ['quality'=>'hd', 'width' => 1, 'link'  =>  null],
                    ['quality'=>'hd', 'width' => 100, 'link'  =>  'hd-download-link-'.$video->vimeo_id],
                    ['quality'=>'sd', 'width' => 100, 'link'  =>  'sd-download-link-'.$video->vimeo_id],
                    ['quality'=>'sd', 'width' => 1, 'link'  =>  null],
                ]
            ]
        ];
    }
}
