<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Trackit\Models\Proposal;

class UpdateProposalRequestTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_not_allow_a_title_longer_than_100_characters()
    {
        $proposal = factory(Proposal::class)->create();
        $header = $this->createAuthHeader();
        $data = [
            'title' => str_random(1000),
            'description' => 'This is a description',
        ];

        $response = $this->json('PUT', 'proposals/'.$proposal->id, $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The title may not be greater than 100 characters.', $jsonObject->title[0]);
    }

    /** @test */
    public function it_should_not_allow_a_missing_title()
    {
        $proposal = factory(Proposal::class)->create();
        $header = $this->createAuthHeader();
        $data = [
            'description' => 'This is a description',
        ];

        $response = $this->json('PUT', 'proposals/'.$proposal->id, $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The title field is required.', $jsonObject->title[0]);
    }

    /** @test */
    public function it_should_not_allow_a_description_longer_than_5000_characters()
    {
        $proposal = factory(Proposal::class)->create();
        $header = $this->createAuthHeader();
        $data = [
            'title' => 'This is a title',
            'description' => str_random(5001),
        ];

        $response = $this->json('PUT', 'proposals/'.$proposal->id, $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The description may not be greater than 5000 characters.', $jsonObject->description[0]);
    }

    /** @test */
    public function it_should_not_allow_a_missing_description()
    {
        $proposal = factory(Proposal::class)->create();
        $header = $this->createAuthHeader();
        $data = [
            'title' => 'This is a title',
        ];

        $response = $this->json('PUT', 'proposals/'.$proposal->id, $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The description field is required.', $jsonObject->description[0]);
    }

    /** @test */
    public function it_should_not_allow_tags_not_in_an_array()
    {
        $proposal = factory(Proposal::class)->create();
        $header = $this->createAuthHeader();
        $data = [
            'title' => 'This is a title',
            'description' => 'This is a description',
            'tags' => 'test',
        ];

        $response = $this->json('PUT', 'proposals/'.$proposal->id, $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The tags must be an array.', $jsonObject->tags[0]);
    }

    /** @test */
    public function it_should_not_allow_more_than_20_tags()
    {
        $proposal = factory(Proposal::class)->create();
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

        $response = $this->json('PUT', 'proposals/'.$proposal->id, $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The tags may not have more than 20 items.', $jsonObject->tags[0]);
    }
}
