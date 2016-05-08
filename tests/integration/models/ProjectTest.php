<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Collection;
use Trackit\Models\Project;
use Trackit\Models\Proposal;
use Trackit\Models\User;
use Trackit\Models\Team;
use Trackit\Models\Role;
use Trackit\Models\Comment;
use Trackit\Models\Workflow;
use Trackit\Models\ProjectRole;
use Trackit\Models\ProjectUser;

class ProjectTest extends TestCase
{
    use DatabaseTransactions;

    private $team;
    private $users;
    private $project;
    private $projectUser;
    private $projectRole;

    /** @test */
    public function it_has_a_proposal_associated_with_it()
    {
        $project = factory(Project::class)->create();
        $proposal = factory(Proposal::class)->create();

        $project->proposal()->associate($proposal);

        $this->assertEquals($proposal->id, $project->proposal_id);
    }

    /** @test */
    public function it_has_a_status()
    {
        $project = factory(Project::class)->create();
        
        $status = in_array($project->status, Project::STATUSES);

        $this->assertTrue($status);
    }

    /** @test */
    public function it_has_a_userteam()
    {
        $project = factory(Project::class)->create();
        $this->setUpTeam();

        $project->team()->associate($this->team);
                
        $this->assertEquals($this->team->id, $project->team_id);
    }

    /** @test */
    public function it_can_have_comments()
    {
        $project = factory(Project::class)->create();
        $comment = factory(Comment::class)->create();

        $project->comments()->save($comment);
       
        $this->assertEquals($comment->id, $project->comments->first()->id);
    }

     /** @test */
    public function it_has_a_workflow()
    {
          $project = factory(Project::class)->create();
          $workflow = factory(Workflow::class)->create();

          $project->workflow()->save($workflow);

          $this->assertEquals($workflow->id, $project->workflow->id);
    }

    /** @test */
    public function it_has_a_stakeholder()
    {
        $this->setUpProjectWithProjectUser('stakeholder');
        $project = $this->project;
        $projectUser = $this->projectUser;
        $projectRole = $this->projectRole;
        
        $this->assertEquals($project->id, $projectUser->project->id);
        $this->assertEquals($projectRole->name, $projectUser->projectRole->name);
    }
    
    /** @test */
    public function it_has_at_least_one_supervisor()
    {
        $this->setUpProjectWithProjectUser('supervisor');
        $project = $this->project;
        $projectUser = $this->projectUser;
        $projectRole = $this->projectRole;
        
        $this->assertEquals($project->id, $projectUser->project->id);
        $this->assertEquals($projectRole->name, $projectUser->projectRole->name);
    }

    /** @test */
    public function it_can_add_project_user()
    {
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create();
        $projectRole = ProjectRole::byName('teacher')->first();
        
        $project->addProjectUser('teacher', $user);

        $this->assertNotNull($project->projectUsers()->where(['user_id' => $user->id, 'project_role_id' => $projectRole->id])->first());
    }

    private function setUpTeam()
    {
        $this->users = new Collection(factory(User::class, 3)->create());
        $this->team = Team::create();
        $team = $this->team;
        $this->users->each(function ($user) use ($team) {
            $user->joinTeam($this->team);
        });
    }

    private function setUpProjectWithProjectUser($role)
    {
        $this->project = factory(Project::class)->create();
        $user = factory(User::class)->create();
        $this->projectRole = ProjectRole::where('name', $role)->first();
    
        $this->projectUser = factory(ProjectUser::class)->create();
        $this->projectUser->user()->associate($user);
        $this->projectUser->project()->associate($this->project);
        $this->projectUser->projectRole()->associate($this->projectRole);
        $this->projectUser->save();
    }

    /** @test */
    public function it_should_allow_edits_from_owner()
    {
        $user = factory(User::class)->create();
        $project = factory(Project::class)->create(['owner_id' => $user->id]);

        $this->assertTrue($project->allowsActionfrom('project:edit', $user));
    }

    /** @test */
    public function it_should_disallow_edits_from_non_author()
    {
        $this->setUpProjectWithProjectUser('stakeholder');
        $this->projectUser->user->role()->associate(Role::byName('customer')->first())->save();

        $this->assertFalse($this->project->allowsActionFrom('project:edit', $this->projectUser->user));
    }

    /** @test */
    public function it_should_allow_edits_from_project_user_with_proper_permissions()
    {
        $this->setUpProjectWithProjectUser('teacher');

        $this->assertTrue($this->project->allowsActionfrom('project:edit', $this->projectUser->user));
    }

    /** @test */
    public function it_should_disallow_edits_from_project_user_without_proper_permissions()
    {
        $this->setUpProjectWithProjectUser('stakeholder');
        $this->projectUser->user->role()->associate(Role::byName('customer')->first())->save();

        $this->assertFalse($this->project->allowsActionfrom('project:edit', $this->projectUser->user));
    }

    /** @test */
    public function it_should_allow_edits_from_global_user_with_proper_permissions()
    {
        $user = factory(User::class)->create();
        $user->role()->associate(Role::byName('administrator')->first())->save();
        $project = factory(Project::class)->create();

        $this->assertTrue($project->allowsActionfrom('project:edit', $user));
    }
}
