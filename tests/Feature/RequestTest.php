<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Http\Services\Auth;
use App\Exceptions\ExpectsJsonException;

class RequestTest extends TestCase
{
    public function test_not_found()
    {
        $response = $this->get('/api/banks');

        $this->assertEquals('Not found.', $response->json('message'));

        $response->assertStatus(404);
    }

    public function test_missin_header_accept()
    {
        $response = $this->get('/api/institutions');

        $this->assertEquals((new ExpectsJsonException())->getMessage(), $response->json('message'));

        $response->assertStatus(400);
    }

    public function test_unauthenticated()
    {
        $response = $this->get('/api/institutions', [
            'accept' => 'application/json'
        ]);

        $this->assertEquals("Unauthenticated.", $response->json('message'));

        $response->assertUnauthorized();
    }

    public function test_authenticated()
    {
        $response = $this->get('/api/institutions', [
            'accept' => 'application/json',
            'authorization' => 'Bearer ' . Auth::token(),
        ]);

        $response->assertSuccessful();
    }
}
