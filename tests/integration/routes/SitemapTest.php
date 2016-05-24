<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SitemapTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_return_a_sitemap()
    {
        $response = $this->json('GET', '/site')->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue(is_object($jsonObject));
        $this->assertObjectHasAttribute('self', $jsonObject);
    }

    /** @test */
    public function it_should_return_a_sitemap_with_user_when_authenticating()
    {
        $header = $this->createAuthHeader();
        $response = $this->json('GET', '/site', [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue(is_object($jsonObject));
        $this->assertObjectHasAttribute('self', $jsonObject);
        $this->assertObjectHasAttribute('user', $jsonObject);
    }
}
