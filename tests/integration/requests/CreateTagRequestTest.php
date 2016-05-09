<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Trackit\Models\Proposal;
use Trackit\Models\Tag;

class CreateTagRequestTest extends TestCase
{
	use DatabaseTransactions;

	/** @test */
    public function it_should_not_allow_a_missing_tags_array()
    {
        $proposal = factory(Proposal::class)->create();
        $data = [
        ];
        $header = $this->createAuthHeader();

        $response = $this->json('POST', 'proposals/'.$proposal->id.'/tags', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The tags field is required.', $jsonObject->tags[0]);
    }

    /** @test */
    public function it_should_not_allow_an_empty_tags_array()
    {
        $proposal = factory(Proposal::class)->create();
        $data = [
        	'tags' => [],
        ];
        $header = $this->createAuthHeader();

        $response = $this->json('POST', 'proposals/'.$proposal->id.'/tags', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The tags field is required.', $jsonObject->tags[0]);
    }

    /** @test */
    public function it_should_not_allow_a_tags_array_with_more_than_20_tags()
    {
        $proposal = factory(Proposal::class)->create();
        $tags = [];
        for ($i = 0; $i < 21; $i++) {
        	$tags[] = factory(Tag::class)->create()->name;
        }
        $data = [
        	'tags' => $tags,
        ];
        $header = $this->createAuthHeader();

        $response = $this->json('POST', 'proposals/'.$proposal->id.'/tags', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The tags may not have more than 20 items.', $jsonObject->tags[0]);
    }

    /** @test */
    public function it_should_not_allow_a_tag_longer_than_20_characters()
    {
        $proposal = factory(Proposal::class)->create();
        $data = [
        	'tags' => [str_random(21)],
        ];
        $header = $this->createAuthHeader();

        $response = $this->json('POST', 'proposals/'.$proposal->id.'/tags', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The tags.0 may not be greater than 20 characters.', $jsonObject->{'tags.0'}[0]);
    }

    /** @test */
    public function it_should_not_allow_a_tag_with_spaces()
    {
        $proposal = factory(Proposal::class)->create();
        $data = [
        	'tags' => [' aa'],
        ];
        $header = $this->createAuthHeader();

        $response = $this->json('POST', 'proposals/'.$proposal->id.'/tags', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The tags.0 format is invalid.', $jsonObject->{'tags.0'}[0]);
    }

    /** @test */
    public function it_should_not_allow_a_tag_to_start_with_a_digit()
    {
        $proposal = factory(Proposal::class)->create();
        $data = [
        	'tags' => ['1asdasd'],
        ];
        $header = $this->createAuthHeader();

        $response = $this->json('POST', 'proposals/'.$proposal->id.'/tags', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The tags.0 format is invalid.', $jsonObject->{'tags.0'}[0]);
    }

    /** @test */
    public function it_should_not_allow_a_tag_to_start_with_an_underscore()
    {
        $proposal = factory(Proposal::class)->create();
        $data = [
        	'tags' => ['_asdasd'],
        ];
        $header = $this->createAuthHeader();

        $response = $this->json('POST', 'proposals/'.$proposal->id.'/tags', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The tags.0 format is invalid.', $jsonObject->{'tags.0'}[0]);
    }

    /** @test */
    public function it_should_allow_a_tag_including_a_hashtag()
    {
        $proposal = factory(Proposal::class)->create();
        $data = [
            'tags' => ['C#'],
        ];
        $header = $this->createAuthHeader();

        $response = $this->json('POST', 'proposals/'.$proposal->id.'/tags', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('C#', $jsonObject->data[0]->name);
    }
}
