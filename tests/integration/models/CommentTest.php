<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Trackit\Models\Comment;
use Trackit\Models\User;

class CommentTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_have_a_body()
    {	
    	$comment = factory(Comment::class)->create(['body' => 'This is a body']);
        $this->assertEquals('This is a body', $comment->body);
    }

    /** @test */
    public function it_should_have_a_created_timestamp()
    {
    	$comment = factory(Comment::class)->create();
    	$this->assertNotNull($comment->created_at);
    	$this->assertInstanceOf(Carbon::class, $comment->created_at);
    }

    /** @test */
    public function it_should_have_a_last_updated_timestamp()
    {
    	$comment = factory(Comment::class)->create();
    	$this->assertNotNull($comment->updated_at);
    	$this->assertInstanceOf(Carbon::class, $comment->updated_at);
    }

    /** @test */
    public function it_should_have_a_user()
    {
    	$user = factory(User::class)->create();
    	$comment = Comment::create([
    		'user_id' => $user->id,
    	]);
    	$this->assertEquals($comment->user->id, $user->id);
    }
}
