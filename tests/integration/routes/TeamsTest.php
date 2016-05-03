<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Trackit\Models\Team;
use Trackit\Models\Proposal;
use Trackit\Models\User;

class TeamsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_return_a_list_of_teams_for_a_proposal()
    {
        $proposal = factory(Proposal::class)->create();
        factory(Team::class, 3)->create()->each(function ($team) use ($proposal) {
            $team->users()->attach(factory(User::class, 2)->create());
            $team->proposal()->associate($proposal);
            $team->save();
        });

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'proposals/'.$proposal->id.'/teams', [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(3, count($jsonObject->data));
    }

    /** @test */
    public function it_should_return_a_team()
    {
        $team = factory(Team::class)->create();

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'teams/'.$team->id, [], $header)->response;

        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($team->id, $jsonObject->data->id);
    }

    /** @test */
    public function it_should_return_a_failure_for_a_non_existing_team()
    {
        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'team/9999999999', [], $header)->response;

        $this->assertEquals(404, $response->getStatusCode());
    }

    /** @test */
    public function it_should_create_a_new_team_on_a_proposal()
    {
        $proposal = factory(Proposal::class)->create();
        $data = [
            'user_ids' => [],
        ];
        $users = factory(User::class, 5)->create();
        foreach ($users as $user) {
            $data['user_ids'][] = $user->id;
        }

        $header = $this->createAuthHeader();
        $response = $this->json('POST', 'proposals/'.$proposal->id.'/teams', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($proposal->id, $jsonObject->data->proposal_id);
        $this->assertEquals(sizeof($data['user_ids']), sizeof($jsonObject->data->users));
    }

    /** @test */
    public function it_should_update_an_existing_team()
    {
        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();
        $data = [
            'users' => [$user->id],
        ];

        $header = $this->createAuthHeader();
        $response = $this->put('teams/'.$team->id, $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($data['users'][0], $jsonObject->data->users[0]->id);
    }

    /** @test */
    public function it_should_delete_an_existing_team()
    {
        $header = $this->createAuthHeader();

        $team = factory(Team::class)->create();

        $response = $this->delete('teams/'.$team->id, [], $header)->response;

        $this->assertEquals(204, $response->getStatusCode());
    }
}
