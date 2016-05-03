<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthenticationTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_allow_user_to_log_in_with_valid_credentials()
    {
        $user = factory(Trackit\Models\User::class)->create(['password' => 'nisse']);

        $data = [
            'username'  => $user->username,
            'password'  => 'nisse',
        ];

        $server = [
            'X-CSRF-TOKEN'  => csrf_token(),
        ];

        $response = $this->post('/auth/login', $data, $server)->response;

        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($user->api_token, $jsonObject->data->api_token);
    }

    /** @test */
    public function it_should_return_error_when_using_incorrect_credentials()
    {
        $user = factory(Trackit\Models\User::class)->create(['password' => 'nisse']);

        $data = [
            'username'  => $user->username,
            'password'  => 'olle',
        ];

        $server = [
            'X-CSRF-TOKEN'  => csrf_token(),
        ];

        $response = $this->post('/auth/login', $data, $server)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals('Incorrect password.', $jsonObject->error);
    }

    /** @test */
    public function it_should_return_error_when_user_does_not_exist()
    {

        $data = [
            'username'  => 'olle',
            'password'  => 'olle',
        ];

        $server = [
            'X-CSRF-TOKEN'  => csrf_token(),
        ];

        $response = $this->post('/auth/login', $data, $server)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals('Unknown username.', $jsonObject->error);
    }
}
