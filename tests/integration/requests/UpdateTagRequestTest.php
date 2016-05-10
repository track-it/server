<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Trackit\Models\Proposal;
use Trackit\Models\Tag;
use Trackit\Models\Role;

class UpdateTagRequestTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_not_allow_a_tag_longer_than_20_characters()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('administrator')->first())->save();
        $tag = factory(Tag::class)->create();
        $data = [
            'name' => str_random(21),
        ];
        $header = $this->createAuthHeader();

        $response = $this->json('PUT', 'tags/'.$tag->id, $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The name may not be greater than 20 characters.', $jsonObject->name[0]);
    }

    /** @test */
    public function it_should_not_allow_a_tag_with_spaces()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('administrator')->first())->save();
        $tag = factory(Tag::class)->create();
        $data = [
            'name' => ' aa',
        ];
        $header = $this->createAuthHeader();

        $response = $this->json('PUT', 'tags/'.$tag->id, $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The name format is invalid.', $jsonObject->name[0]);
    }

    /** @test */
    public function it_should_not_allow_a_tag_to_start_with_a_digit()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('administrator')->first())->save();
        $tag = factory(Tag::class)->create();
        $data = [
            'name' => '1asdasd',
        ];
        $header = $this->createAuthHeader();

        $response = $this->json('PUT', 'tags/'.$tag->id, $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The name format is invalid.', $jsonObject->name[0]);
    }

    /** @test */
    public function it_should_not_allow_a_tag_to_start_with_an_underscore()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('administrator')->first())->save();
        $tag = factory(Tag::class)->create();
        $data = [
            'name' => '_asdasd',
        ];
        $header = $this->createAuthHeader();

        $response = $this->json('PUT', 'tags/'.$tag->id, $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The name format is invalid.', $jsonObject->name[0]);
    }
}
