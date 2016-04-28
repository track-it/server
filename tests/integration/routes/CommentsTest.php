<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Trackit\Models\Comment;
use Trackit\Models\User;
use Trackit\Models\Proposal;

class CommentsTest extends TestCase
{	
	use DatabaseTransactions;

	/** @test */
	public function it_should_create_a_new_comment_on_a_commentable_resource()
	{	
		$proposal = factory(Proposal::class)->create();
		$user = factory(User::class)->create();

		$url = 'proposals/'.$proposal->id.'/comments';
		$content = ['body' => 'This is a body.', 'author_id' => $user->id];
		$header = $this->createAuthHeader();

        $response = $this->post($url, $content, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('This is a body.', $jsonObject->items[0]->body);
	}

	/** @test */
	public function it_should_return_all_comments_on_a_commentable_resource()
	{
		$proposal = factory(Proposal::class)->create();
		$user = factory(User::class)->create();

		$url = 'proposals/'.$proposal->id.'/comments';
		$header = $this->createAuthHeader();

		$content1 = ['body' => 'This is a body.', 'author_id' => $user->id];
		$content2 = ['body' => 'This is another body.', 'author_id' => $user->id];

		$this->post($url, $content1, $header);
		$this->post($url, $content2, $header);

		$response = $this->get($url, $header)->response;
		$jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(2, count($jsonObject->items));
	}

	/** @test */
	public function it_should_return_a_failure_for_a_non_existing_resource()
	{
		$user = factory(User::class)->create();
		$url = 'proposals/9999999999999/comments';
		$content = ['body' => 'This is a body.', 'author_id' => $user->id];
		$header = $this->createAuthHeader();

        $response = $this->post($url, $content, $header)->response;
		
        $this->assertEquals(404, $response->getStatusCode());
	}

	/** @test */
    public function it_should_delete_an_existing_comment_on_a_commentable_resource()
    {
        $comment = factory(Comment::class)->create();
        $header = $this->createAuthHeader();

        $response = $this->delete('comments/'.$comment->id, [], $header)->response;

        $this->assertEquals(204, $response->getStatusCode());
    }

	/** @test */
    public function it_should_update_an_existing_comment_on_a_commentable_resource()
    {
        $comment = factory(Comment::class)->create();
        $data = ['body' => 'new body'];

        $header = $this->createAuthHeader();

        $response = $this->put('comments/'.$comment->id, $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($data['body'], $jsonObject->items[0]->body);
    }

    /** @test */
    public function it_should_return_a_comment_on_commments_endpoint()
    {
        $comment = factory(Comment::class)->create();

        $header = $this->createAuthHeader();

        $response = $this->get('comments/'.$comment->id, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }
}
