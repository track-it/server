<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Trackit\Models\Attachment;
use Trackit\Models\Proposal;
use Trackit\Models\User;

class AttachmentTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_has_an_uploader()
    {
    	$user = factory(User::class)->create();

        $attachment = factory(Attachment::class)->create();

        $attachment->uploader()->associate($user);

        $hasUploader = !! $attachment->uploader;

        $this->assertTrue($hasUploader);
    }

    /** @test */
    public function it_has_a_title()
    {
        $attachment = factory(Attachment::class)->create();
        
        $hasTitle = !! $attachment->title;

        $this->assertTrue($hasTitle);
    }

    /** @test */
    public function it_has_an_url()
    {
        $attachment = factory(Attachment::class)->create();
        
        $hasUrl = !! $attachment->url;

        $this->assertTrue($hasUrl);
    }

    /** @test */
    public function it_is_connected_to_a_source()
    {
    	$proposal = factory(Proposal::class)->create();

        $attachment = factory(Attachment::class)->create();

        $attachment->source()->associate($proposal);
        
        $this->assertEquals($proposal, $attachment->source);
    }

}
