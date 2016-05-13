<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Trackit\Models\Role;
use Trackit\Models\Proposal;

class ShowProposalRequestTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_allow_guest_user_to_see_approved_proposal()
    {
        $proposal = factory(Proposal::class)->create();
        $proposal->status = Proposal::APPROVED;
        $proposal->save();

        $response = $this->json('GET', 'proposals/'.$proposal->id)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_disallow_guest_user_to_see_unapproved_proposal()
    {
        $proposal = factory(Proposal::class)->create();
        $proposal->status = Proposal::NOT_APPROVED;
        $proposal->save();

        $response = $this->json('GET', 'proposals/'.$proposal->id)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_should_disallow_regular_user_to_see_unapproved_proposal()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('student')->first())->save();
        $proposal = factory(Proposal::class)->create();
        $proposal->status = Proposal::NOT_APPROVED;
        $proposal->save();

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'proposals/'.$proposal->id, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_should_allow_user_with_teacher_role_to_see_unapproved_proposal()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('teacher')->first())->save();
        $proposal = factory(Proposal::class)->create();
        $proposal->status = Proposal::NOT_APPROVED;
        $proposal->save();

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'proposals/'.$proposal->id, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_allow_user_with_administrator_role_to_see_approved_proposal()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('administrator')->first())->save();
        $proposal = factory(Proposal::class)->create();
        $proposal->status = Proposal::APPROVED;
        $proposal->save();

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'proposals/'.$proposal->id, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_allow_user_with_administrator_role_to_see_archived_proposal()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('administrator')->first())->save();
        $proposal = factory(Proposal::class)->create();
        $proposal->status = Proposal::ARCHIVED;
        $proposal->save();

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'proposals/'.$proposal->id, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }
}
