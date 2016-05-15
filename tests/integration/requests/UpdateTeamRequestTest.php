<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Trackit\Models\Role;
use Trackit\Models\Proposal;
use Trackit\Models\User;
use Trackit\Models\Project;
use Trackit\Models\Team;

class UpdateTeamRequestTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_allow_users_with_administrator_role_to_update_team()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('administrator')->first())->save();
        $proposal = factory(Proposal::class)->create();
        $team = factory(Team::class)->create();
        $team->proposal()->associate($proposal)->save();
        $data = [
            'proposal_id' => 2,
        ];

        $header = $this->createAuthHeader();
        $response = $this->json('PUT', 'teams/'.$team->id, $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_disallow_users_without_administrator_role_to_update_team()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('student')->first())->save();
        $proposal = factory(Proposal::class)->create();
        $team = factory(Team::class)->create();
        $team->proposal()->associate($proposal)->save();
        $data = [
            'proposal_id' => 2,
        ];

        $header = $this->createAuthHeader();
        $response = $this->json('PUT', 'teams/'.$team->id, $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(403, $response->getStatusCode());
    }
}
