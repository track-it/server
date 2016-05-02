<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Trackit\Models\Proposal;
use Trackit\Models\User;

class UpdateProposalRequestTest extends TestCase
{
    use DatabaseTransactions;

    // -------------------------------------------------------------------
    //                          AUTHORIZATION
    // -------------------------------------------------------------------

    /** @test */
    public function it_should_allow_an_author_to_update()
    {        
        $proposal = factory(Proposal::class)->create(['author_id' => $this->getUser()->id]);
        $header = $this->createAuthHeader();
        $data = [
            'title' => 'This is a title',
            'description' => 'This is a description',
        ];

        $response = $this->json('PUT', 'proposals/'.$proposal->id, $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('This is a title', $jsonObject->data->title);
    }

    /** @test */
    public function it_should_disallow_a_non_author_to_update()
    {
        $proposal = factory(Proposal::class)->create();
        $originalTitle = $proposal->title;
        $data = [
            'title' => 'This is a title',
            'description' => 'This is a description',
        ];
        $nonAuthorHeader = $this->createAuthHeader();

        $response = $this->json('PUT', 'proposals/'.$proposal->id, $data, $nonAuthorHeader)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals($originalTitle, $proposal->title);
    }
}
