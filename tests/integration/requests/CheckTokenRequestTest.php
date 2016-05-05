<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CheckTokenRequestTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_require_username()
    {
        $user = $this->getUser();
        $header = $this->createAuthHeader();
        $data = [
            'api_token' => $user->api_token,
        ];

        $response = $this->json('POST', 'auth/check', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The username field is required.', $jsonObject->username[0]);
    }

    /** @test */
    public function it_should_require_username_to_be_a_string()
    {
        $user = $this->getUser();
        $header = $this->createAuthHeader();
        $data = [
            'username' => 1,
            'api_token' => $user->api_token,
        ];

        $response = $this->json('POST', 'auth/check', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The username must be a string.', $jsonObject->username[0]);
    }

    /** @test */
    public function it_should_require_api_token()
    {
        $user = $this->getUser();
        $header = $this->createAuthHeader();
        $data = [
            'username' => $user->username,
        ];

        $response = $this->json('POST', 'auth/check', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The api token field is required.', $jsonObject->api_token[0]);
    }

    /** @test */
    public function it_should_require_api_token_to_be_a_string()
    {
        $user = $this->getUser();
        $header = $this->createAuthHeader();
        $data = [
            'username' => $user->username,
            'api_token' => ['asd'],
        ];

        $response = $this->json('POST', 'auth/check', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The api token must be a string.', $jsonObject->api_token[0]);
    }
}
