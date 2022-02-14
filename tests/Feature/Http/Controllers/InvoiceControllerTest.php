<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvoiceControllerTest extends TestCase
{



    /**
     * @test
     *
     */
    public function create_returns_a_view()
    {
        $response = $this->get(route('invoice.create'));

        $response->assertStatus(200);
        $response->assertViewIs('invoice.create');
    }
}
