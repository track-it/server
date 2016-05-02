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
        $tag = factory(Trackit\Models\Tag::class)->create();

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'tags/'.$tag->id, [], $header)->response;

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function routes_return_error_without_valid_api_token()
    {
        $tag = factory(Trackit\Models\Tag::class)->create();

        $response = $this->json('GET', 'tags/'.$tag->id)->response;

        $this->assertEquals(401, $response->getStatusCode());
    }
}
