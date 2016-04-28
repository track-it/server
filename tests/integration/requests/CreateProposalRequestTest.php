<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Faker\Faker;

class CreateProposalRequestTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_not_allow_a_title_longer_than_100_characters()
    {
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
        $header = $this->createAuthHeader();
        $data = [
            'title' => 'This is a title',
        ];

        $response = $this->json('POST', 'proposals', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The description field is required.', $jsonObject->description[0]);
    }
}
