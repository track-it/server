<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Trackit\Models\Project;
use Trackit\Models\ProjectUser;
use Trackit\Models\User;
use Trackit\Models\ProjectRole;

class ProjectUserTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_have_a_user()
    {
        $projectUser = factory(ProjectUser::Class)->create();
        $user = factory(User::Class)->create();

        $projectUser->user()->associate($user);

        $this->assertEquals($user->id, $projectUser->user->id);
    }

    /** @test */
    public function it_should_have_a_project()
    {
        $projectUser = factory(ProjectUser::Class)->create();
        $project = factory(Project::Class)->create();
        
        $projectUser->project()->associate($project);

        $this->assertEquals($project->id, $projectUser->project->id);
    }

     /** @test */
    public function it_should_have_a_project_role()
    {
        $projectUser = factory(ProjectUser::Class)->create();
        $projectRole = ProjectRole::find(1);

        $projectUser->projectRole()->associate($projectRole);
        
        $this->assertEquals($projectRole->first()->name, $projectUser->projectRole->name);
    }
}