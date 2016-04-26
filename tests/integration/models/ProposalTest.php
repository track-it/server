<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Trackit\Models\Proposal;
use Trackit\Models\Attachment;

class ProposalTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_has_a_status()
    {
        $proposal = factory(Proposal::class)->create();
        
        $hasStatus = in_array($proposal->status, Proposal::STATUSES);

        $this->assertTrue($hasStatus);
    }

    /** @test */
    public function it_has_a_creator()
    {
        $proposal = factory(Proposal::class)->create();
        
        $hasCreator = !! $proposal->creator();

        $this->assertTrue($hasCreator);
    }

    /** @test */
    public function it_can_have_an_attachment()
    {
        $attachment = factory(Attachment::class)->create();

        $proposal = factory(Proposal::class)->create();
        
        $proposal->attachments()->save($attachment);

        $this->assertEquals(1, $proposal->attachments->count());
    }
}
