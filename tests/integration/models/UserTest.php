<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Trackit\Models\User;
use Trackit\Models\Proposal;
use Trackit\Models\Project;
use Trackit\Models\ProjectUser;
use Trackit\Models\Role;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_has_an_api_token()
    {
        $user = factory(User::class)->create();

        $hasApiToken = !! $user->api_token;

        $this->assertTrue($hasApiToken);
    }

    /** @test */
    public function it_can_own_a_proposal()
    {
        $proposal = factory(Proposal::class)->create();

        $user = factory(User::class)->create();
        $user->proposals()->save($proposal);

        $this->assertEquals($proposal->id, $user->proposals->first()->id);
    }

    /** @test */
    public function it_has_a_role()
    {
        $role = factory(Role::class)->create();

        $user = factory(User::class)->create();
        $user->role()->associate($role);

        $this->assertEquals($role->name, $user->role->name);
    }

    /** @test */
    public function it_can_have_projects()
    {
        $user = factory(User::class)->create();
        $projects = factory(Project::class, 5)->create();
        $projects->each(function ($project) use ($user) {
            $project->addParticipant('student', $user);
        });

        $this->assertEquals(5, $user->projects->count());
    }
}
