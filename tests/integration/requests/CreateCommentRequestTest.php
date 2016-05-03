<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Trackit\Models\Proposal;
use Trackit\Models\Comment;

class CreateCommentRequestTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_not_allow_a_body_longer_than_5000_characters()
    {
        $header = $this->createAuthHeader();
        $proposal = factory(Proposal::class)->create();
        $comment = factory(Comment::class)->create();
        $data = [
            'body' => str_random(5001),
        ];

        $response = $this->json('POST', 'proposals/' . $proposal->id . '/comments', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The body may not be greater than 5000 characters.', $jsonObject->body[0]);
    }

    /** @test */
    public function it_should_not_allow_a_missing_body()
    {
        $header = $this->createAuthHeader();
        $proposal = factory(Proposal::class)->create();
        $comment = factory(Comment::class)->create();
        $data = [
        ];

        $response = $this->json('POST', 'proposals/' . $proposal->id . '/comments', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The body field is required.', $jsonObject->body[0]);
    }
}
