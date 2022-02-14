<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class MockTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        //
        $mock = Mockery::mock();

        $mock->shouldReceive('foo')
            ->with('bar')
            ->andReturn('baz')
        ;

        $this->assertEquals('baz', $mock->foo('bar'));

        $mock->shouldReceive('quix')
            ->andReturnNull()
        ;

        $this->assertNull($mock->quix());
    }

    public function testSpying()
    {
        $mock = Mockery::spy();

        $this->assertNull($mock->qux());
    }
}
