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
	public function it_should_create_a_new_comment_on_commentable_resource()
	{	
		$proposal = factory(Proposal::class)->create();
		$user = factory(User::class)->create();

		$url = 'proposals/'.$proposal->id.'/comments';
        $response = $this->post($url, ['body' => 'This is a body.', 'author_id' => $user->id])->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('This is a body.', $jsonObject->items[0]->body);
	}
}
