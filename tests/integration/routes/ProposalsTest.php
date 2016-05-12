<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Trackit\Models\Proposal;
use Trackit\Models\Role;

class ProposalsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_return_a_filtered_collection_of_proposals_with_pagination()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('teacher')->first());
        $user->save();
        factory(Proposal::class, 10)->create(['status' => Proposal::NOT_APPROVED]);
        factory(Proposal::class, 3)->create(['status' => Proposal::UNDER_REVIEW]);
        factory(Proposal::class, 5)->create(['status' => Proposal::APPROVED]);

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'proposals', [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertObjectHasAttribute('data', $jsonObject);
        $this->assertInternalType('array', $jsonObject->data);
    }

    /** @test */
    public function it_should_return_a_filtered_collection_of_proposals_including_your_own_with_pagination()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('customer')->first());
        $user->save();
        factory(Proposal::class, 10)->create(['status' => Proposal::NOT_APPROVED]);
        factory(Proposal::class, 3)->create(['status' => Proposal::UNDER_REVIEW]);
        factory(Proposal::class, 5)->create(['status' => Proposal::APPROVED]);
        factory(Proposal::class)->create(['status' => Proposal::NOT_APPROVED, 'author_id' => $user->id]);

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'proposals', [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertObjectHasAttribute('data', $jsonObject);
        $this->assertInternalType('array', $jsonObject->data);
        $this->assertEquals(6, sizeof($jsonObject->data));
    }

    /** @test */
    public function it_should_return_approved_proposals_with_pagination_without_authorization()
    {
        factory(Proposal::class, 10)->create(['status' => Proposal::NOT_APPROVED]);
        factory(Proposal::class, 3)->create(['status' => Proposal::UNDER_REVIEW]);
        factory(Proposal::class, 5)->create(['status' => Proposal::APPROVED]);

        $response = $this->json('GET', 'proposals')->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertObjectHasAttribute('data', $jsonObject);
        $this->assertInternalType('array', $jsonObject->data);
    }

    /** @test */
    public function it_should_return_a_single_proposal()
    {
        $proposal = factory(Proposal::class)->create();

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'proposals/'.$proposal->id, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertObjectHasAttribute('data', $jsonObject);
        $this->assertEquals($proposal->id, $jsonObject->data->id);
    }

    /** @test */
    public function it_should_return_a_failure_for_a_non_existing_proposal()
    {
        $response = $this->get('proposals/9999999999')->response;

        $this->assertEquals(404, $response->getStatusCode());
    }

    /** @test */
    public function it_should_create_a_new_proposal()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('customer')->first())->save();
        $header = $this->createAuthHeader();
        $data = [
            'title' => 'This is a title',
            'description' => 'This is a description',
        ];

        $response = $this->json('POST', 'proposals', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('This is a title', $jsonObject->data->title);
    }

    /** @test */
    public function it_should_create_a_new_proposal_with_tags()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('customer')->first())->save();
        $header = $this->createAuthHeader();
        $proposalContent = [
            'title' => 'This is a title',
            'description' => 'This is a description',
            'tags' => [
                'tagOne',
                'tagTwo',
            ],
        ];

        $response = $this->json('POST', 'proposals', $proposalContent, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('This is a title', $jsonObject->data->title);
        $this->assertEquals('tagOne', $jsonObject->data->tags[0]->name);
        $this->assertEquals('tagTwo', $jsonObject->data->tags[1]->name);
    }

    /** @test */
    public function it_should_update_an_existing_proposal()
    {
        $header = $this->createAuthHeader();
        $data = [
            'title' => 'This is a title',
            'description' => 'This is a description',
        ];
        $proposal = factory(Proposal::class)->create(['author_id' => $this->getUser()->id]);

        $response = $this->json('PUT', 'proposals/'.$proposal->id, $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('This is a title', $jsonObject->data->title);
    }

    /** @test */
    public function it_should_delete_an_existing_proposal()
    {
        $header = $this->createAuthHeader();

        $proposal = factory(Proposal::class)->create(['author_id' => $this->getUser()->id]);

        $response = $this->delete('proposals/'.$proposal->id, [], $header)->response;

        $this->assertEquals(204, $response->getStatusCode());
    }
}
