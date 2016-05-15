<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Trackit\Models\Proposal;
use Trackit\Models\Role;
use Trackit\Models\User;

class UsersTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_return_your_user()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('teacher')->first())->save();
        $user->proposals()->save(factory(Proposal::class)->create());

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'me', [], $header)->response;

        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($user->username, $jsonObject->data->username);
        $this->assertEquals(1, sizeof($jsonObject->data->proposals));
        $this->assertEquals('teacher', $jsonObject->data->role->name);
    }

    /** @test */
    public function it_should_return_a_user()
    {
        $user = factory(User::class)->create();
        $user->role()->associate(Role::byName('teacher')->first())->save();
        $user->proposals()->save(factory(Proposal::class)->create());

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'users/'.$user->id, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($user->username, $jsonObject->data->username);
        $this->assertEquals(1, sizeof($jsonObject->data->proposals));
        $this->assertEquals('teacher', $jsonObject->data->role->name);
    }

    /** @test */
    public function it_should_not_include_api_token_when_returning_a_user()
    {
        $user = factory(User::class)->create();
        $user->role()->associate(Role::byName('teacher')->first())->save();
        $user->proposals()->save(factory(Proposal::class)->create());

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'users/'.$user->id, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($user->username, $jsonObject->data->username);
        $this->assertEquals(1, sizeof($jsonObject->data->proposals));
        $this->assertEquals('teacher', $jsonObject->data->role->name);
        $this->assertObjectNotHasAttribute('api_token', $jsonObject->data);
    }

    /** @test */
    public function it_should_return_a_paginated_list_of_users()
    {
        factory(User::class, 30)->create();

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'users', [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertObjectHasAttribute('total', $jsonObject);
        $this->assertEquals(20, sizeof($jsonObject->data));
    }
}
