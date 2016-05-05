<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Trackit\Models\Role;

class CreateProposalRequestTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_allow_users_with_submit_permission_to_create_proposal()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('teacher')->first())->save();
        $header = $this->createAuthHeader();
        $data = [
            'title' => 'This is a title',
            'description' => 'This is a description',
        ];

        $response = $this->json('POST', 'proposals', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_not_allow_a_title_longer_than_100_characters()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('teacher')->first())->save();
        $header = $this->createAuthHeader();
        $data = [
            'title' => str_random(1000),
            'description' => 'This is a description',
        ];

        $response = $this->json('POST', 'proposals', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The title may not be greater than 100 characters.', $jsonObject->title[0]);
    }

    /** @test */
    public function it_should_not_allow_a_missing_title()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('teacher')->first())->save();
        $header = $this->createAuthHeader();
        $data = [
            'description' => 'This is a description',
        ];

        $response = $this->json('POST', 'proposals', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The title field is required.', $jsonObject->title[0]);
    }

    /** @test */
    public function it_should_not_allow_a_description_longer_than_5000_characters()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('teacher')->first())->save();
        $header = $this->createAuthHeader();
        $data = [
            'title' => 'This is a title',
            'description' => str_random(5001),
        ];

        $response = $this->json('POST', 'proposals', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The description may not be greater than 5000 characters.', $jsonObject->description[0]);
    }

    /** @test */
    public function it_should_not_allow_a_missing_description()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('teacher')->first())->save();
        $header = $this->createAuthHeader();
        $data = [
            'title' => 'This is a title',
        ];

        $response = $this->json('POST', 'proposals', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The description field is required.', $jsonObject->description[0]);
    }

    /** @test */
    public function it_should_not_allow_tags_not_in_an_array()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('teacher')->first())->save();
        $header = $this->createAuthHeader();
        $data = [
            'title' => 'This is a title',
            'description' => 'This is a description',
            'tags' => 'test',
        ];

        $response = $this->json('POST', 'proposals', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The tags must be an array.', $jsonObject->tags[0]);
    }

    /** @test */
    public function it_should_not_allow_more_than_20_tags()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('teacher')->first())->save();
        $faker = Faker\Factory::create();
        $tags = [];
        for ($i = 0; $i < 21; $i++) {
            $tags[] = $faker->word();
        }
        $header = $this->createAuthHeader();
        $data = [
            'title' => 'This is a title',
            'description' => 'This is a description',
            'tags' => $tags,
        ];

        $response = $this->json('POST', 'proposals', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The tags may not have more than 20 items.', $jsonObject->tags[0]);
    }
}
