<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Trackit\Models\Role;
use Trackit\Models\Proposal;
use Trackit\Models\Team;
use Trackit\Models\User;

class CreateProjectRequestTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_allow_users_with_teacher_role_to_create_project()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('teacher')->first())->save();
        $proposal = factory(Proposal::class)->create(['author_id' => $this->getUser()->id]);
        $team = factory(Team::class)->create();
        factory(User::class, 5)->create()->each(function ($user) use ($team) {
            $team->users()->attach($user->id);
        });
        $data = [
            'title' => 'New Project',
            'team_id' => $team->id,
            'tags' => ['tag1', 'tag2'],
        ];

        $header = $this->createAuthHeader();
        $response = $this->json('POST', 'proposals/'.$proposal->id.'/projects', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_return_error_when_users_without_teacher_role_create_project()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('student')->first())->save();
        $proposal = factory(Proposal::class)->create(['author_id' => $this->getUser()->id]);
        $team = factory(Team::class)->create();
        factory(User::class, 5)->create()->each(function ($user) use ($team) {
            $team->users()->attach($user->id);
        });
        $data = [
            'title' => 'New Project',
            'team_id' => $team->id,
            'tags' => ['tag1', 'tag2'],
        ];

        $header = $this->createAuthHeader();
        $response = $this->json('POST', 'proposals/'.$proposal->id.'/projects', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(403, $response->getStatusCode());
    }
}
