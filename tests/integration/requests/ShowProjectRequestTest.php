<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Trackit\Models\Role;
use Trackit\Models\Proposal;
use Trackit\Models\Project;

class ShowProjectRequestTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_allow_guest_user_to_see_completed_project()
    {
        $project = factory(Project::class)->create();
        $project->status = Project::COMPLETED;
        $project->save();

        $response = $this->json('GET', 'projects/'.$project->id)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_disallow_guest_user_to_see_ongoing_project()
    {
        $project = factory(Project::class)->create();
        $project->status = Project::NOT_COMPLETED;
        $project->save();

        $response = $this->json('GET', 'projects/'.$project->id)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_should_disallow_user_not_in_project_to_see_ongoing_project()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('student')->first())->save();
        $project = factory(Project::class)->create();
        $project->status = Project::NOT_COMPLETED;
        $project->save();

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'projects/'.$project->id, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_should_allow_project_student_to_see_ongoing_project()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('student')->first())->save();
        $project = factory(Project::class)->create();
        $project->addProjectUser('student', $user);
        $project->status = Project::NOT_COMPLETED;
        $project->save();

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'projects/'.$project->id, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_allow_project_student_to_see_published_project()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('student')->first())->save();
        $project = factory(Project::class)->create();
        $project->addProjectUser('student', $user);
        $project->status = Project::PUBLISHED;
        $project->save();

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'projects/'.$project->id, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }
}
