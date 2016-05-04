<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Trackit\Models\User;

class AuthenticationTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_allow_user_to_log_in_with_valid_credentials()
    {
        $user = factory(User::class)->create(['password' => 'nisse']);

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
        $user = factory(User::class)->create(['password' => 'nisse']);

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

    /** @test */
    public function it_should_create_a_new_user()
    {
        $data = [
            'username' => 'newuser',
            'password' => 'newpassword',
        ];
        
        $response = $this->json('POST', 'auth/register', $data)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($data['username'], $jsonObject->data->username);
    }

    /** @test */
    public function it_should_return_an_error_when_creating_a_user_with_existing_username()
    {
        $data = [
            'username' => 'newuser',
            'password' => 'newpassword',
        ];
        factory(User::class)->create($data);
        
        $response = $this->json('POST', 'auth/register', $data)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('User already exists.', $jsonObject->error);
    }

    /** @test */
    public function it_should_return_true_when_checking_valid_token()
    {
        $user = $this->getUser();
        $data = [
            'api_token' => $user->api_token,
        ];

        $response = $this->json('POST', 'auth/check', $data)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($jsonObject->valid);
    }

    /** @test */
    public function it_should_return_false_when_checking_invalid_token()
    {
        $data = [
            'api_token' => str_random(100),
        ];

        $response = $this->json('POST', 'auth/check', $data)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($jsonObject->valid);
    }
}
