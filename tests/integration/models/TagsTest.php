<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Trackit\Models\Tag;
use Trackit\Models\Proposal;

class TagsTest extends TestCase
{
	use DatabaseTransactions;

    /** @test */
    public function it_has_a_name()
    {
        $tag = Tag::create(['name' => 'test']);

        $this->assertEquals('test', $tag->name);
    }

    /** @test */
    public function it_can_be_assigned_to_proposals()
    {
        $proposal = factory(Proposal::class)->create();

        $tag = Tag::create(['name' => 'test']);

        $tag->proposals()->attach($proposal->id);

        $this->assertTrue($tag->proposals->count() == 1) ;
    }
}
