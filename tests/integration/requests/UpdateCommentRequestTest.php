<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Trackit\Models\Comment;
use Trackit\Models\User;

class UpdateCommentRequestTest extends TestCase
{
    use DatabaseTransactions;

    // -------------------------------------------------------------------
    //                          AUTHORIZATION
    // -------------------------------------------------------------------

    /** @test */
    public function it_should_allow_an_author_to_update()
    {        
        $comment = factory(Comment::class)->create(['author_id' => $this->getUser()->id]);
        $header = $this->createAuthHeader();
        $data = [
            'body' => 'This is a body'
        ];

        $response = $this->json('PUT', 'comments/'.$comment->id, $data, $header)->response;
        // dd($response);
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('This is a body', $jsonObject->data->body);
    }

    /** @test */
    public function it_should_disallow_a_non_author_to_update()
    {
        $comment = factory(Comment::class)->create();
        $originalBody = $comment->body;
        $data = [
            'title' => 'This is a title',
            'description' => 'This is a description',
        ];
        $nonAuthorHeader = $this->createAuthHeader();

        $response = $this->json('PUT', 'comments/'.$comment->id, $data, $nonAuthorHeader)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals($originalBody, $comment->body);
    }
}