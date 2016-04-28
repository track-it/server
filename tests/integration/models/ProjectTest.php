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
use Trackit\Models\Course;

class ProjectTest extends TestCase
{
    use DatabaseTransactions;

    private $team;
    private $users;

    /** @test */
    public function it_has_a_proposal_associated_with_it()
    {
        $project = factory(Project::class)->create();

        $proposal = $project->proposal_id;

        $this->assertNotNull($proposal);
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
                
        $this->assertNotNull($project->team_id);
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
        $hasComment = !! $project->comments();

        $this->assertTrue($hasComment);
    }

    /** @test */
    public function it_belongs_to_a_course()
    {
        $project = factory(Project::class)->create();
        $course = factory(Course::class)->create();

        $project->course()->associate($project);
        $hasCourse = !! $project->course();

        $this->assertTrue($hasCourse);
    }

    /** @test */
    public function it_has_at_least_one_supervisor()
    {

    }

    /** @test */
    public function it_has_a_workflow()
    {

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
