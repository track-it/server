<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Collection;
use Trackit\Models\Project;
use Trackit\Models\Proposal;
use Trackit\Models\User;
use Trackit\Models\Team;
use Trackit\Models\Comment;
use Trackit\Models\Workflow;

class ProjectTest extends TestCase
{
    use DatabaseTransactions;

    private $team;
    private $users;

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
    public function it_has_an_owner()
    {
        $project = factory(Project::class)->create();
        $owner = factory(User::class)->create();

        $project->owner()->associate($owner);
        
        $this->assertEquals($owner->id, $project->owner_id);
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
    public function it_has_at_least_one_supervisor()
    {
        $project = factory(Project::class)->create();
        $supervisor = factory(User::class)->create();

        $project->supervisor()->attach($supervisor);

        $this->assertEquals($supervisor->id, $project->supervisor->first()->id);
    }

    /** @test */
    public function it_can_have_more_than_one_supervisor()
    {
        $project = factory(Project::class)->create();
        $supervisors = factory(User::class, 3)->create();

        $project->supervisor()->attach($supervisors);

        $this->assertEquals($supervisors->count(), $project->supervisor->count());
    }

    private function setUpTeam()
    {
        $this->users = new Collection(factory(User::class, 3)->create());
        $this->team = Team::create(['course' => 'da350a']);
        $team = $this->team;
        $this->users->each(function ($user) use ($team) {
            $user->joinTeam($this->team);
        });
    }
}
