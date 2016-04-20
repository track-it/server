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

        $response = $this->get('/proposals/'.$proposal->id)->response;

        $jsonObject = json_decode($response->getContent());

        $this->assertObjectHasAttribute('items', $jsonObject);
        $this->assertInternalType('array', $jsonObject->items);
        $this->assertCount(1, $jsonObject->items);
    }

    /** @test */
    public function it_should_return_a_failure_for_a_non_existing_proposal()
    {
        $response = $this->get('/proposals/9999999999')->response;

        $this->assertEquals(404, $response->getStatusCode());
    }

    /** @test */
    public function it_should_create_a_new_proposal()
    {
        $response = $this->post('/proposals', ['title' => 'Kebab'])->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals('Kebab', $jsonObject->items[0]->title);
    }
}
