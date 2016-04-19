<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Trackit\Models\Proposal;

class ProposalsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_return_a_collection_of_proposals()
    {
        $response = $this->visit('/proposals')->response;

        $jsonObject = json_decode($response->getContent());

        $this->assertObjectHasAttribute('items', $jsonObject);
        $this->assertInternalType('array', $jsonObject->items);
    }

    /** @test */
    public function it_should_return_a_single_proposal()
    {
        $proposal = factory(Proposal::class)->create();

        $response = $this->visit('/proposals/'.$proposal->id)->response;

        $jsonObject = json_decode($response->getContent());

        $this->assertObjectHasAttribute('items', $jsonObject);
        $this->assertInternalType('array', $jsonObject->items);
        $this->assertCount(1, $jsonObject->items);
    }
}
