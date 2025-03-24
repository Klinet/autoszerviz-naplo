<?php

namespace Tests\Feature\Domains\Car;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CarTest extends TestCase
{
    use RefreshDatabase;

    public function test_example()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
