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
	public function it_should_show_all_comments_on_a_commentable_resource()
	{
		$proposal = factory(Proposal::class)->create();
		$user = factory(User::class)->create();

		$url = 'proposals/'.$proposal->id.'/comments';
		$content1 = ['body' => 'This is a body.', 'author_id' => $user->id];
		$content2 = ['body' => 'This is another body.', 'author_id' => $user->id];
		$header = $this->createAuthHeader();

		$this->get($url, $content1, $header);
		$this->get($url, $content2, $header);

		$response = $this->get($url, $header)->response;
		$jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(2, $jsonObject->items->count());
	}
}
