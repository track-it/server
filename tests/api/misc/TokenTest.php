<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TokenTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function routes_require_valid_api_token()
    {
        $user = factory(Trackit\Models\User::class)->create();

        $server = [
            'Authorization' => "Bearer $user->api_token",
        ];

        $response = $this->get('/', $server)->response;

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function routes_return_error_without_valid_api_token()
    {
        $user = factory(Trackit\Models\User::class)->create();

        $server = [
            'Accept'  => 'text/json',
        ];

        $response = $this->get('/', $server)->response;

        $this->assertEquals(401, $response->getStatusCode());
    }
}
