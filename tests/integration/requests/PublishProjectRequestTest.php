<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Trackit\Models\Project;
use Trackit\Models\Role;

class PublishProjectRequestTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_allow_project_student_to_publish_project_if_status_is_completed()
    {
        $project = factory(Project::class)->create();
        $user = $this->getUser();
        $user->role()->associate(Role::byName('student')->first())->save();
        $project->addParticipant('student', $user);
        $project->status = Project::COMPLETED;
        $project->save();
        $header = $this->createAuthHeader();
        $data = [
            'status' => Project::PUBLISHED,
        ];

        $response = $this->json('POST', 'projects/'.$project->id.'/publish', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_not_allow_project_student_to_publish_project_if_status_is_not_completed()
    {
        $project = factory(Project::class)->create();
        $user = $this->getUser();
        $user->role()->associate(Role::byName('student')->first())->save();
        $project->addParticipant('student', $user);
        $project->status = Project::NOT_COMPLETED;
        $project->save();
        $header = $this->createAuthHeader();
        $data = [
            'status' => Project::PUBLISHED,
        ];

        $response = $this->json('POST', 'projects/'.$project->id.'/publish', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_should_not_allow_project_teacher_to_publish_uncompleted_project()
    {
        $project = factory(Project::class)->create();
        $user = $this->getUser();
        $user->role()->associate(Role::byName('teacher')->first())->save();
        $project->addParticipant('teacher', $user);
        $project->status = Project::NOT_COMPLETED;
        $project->save();
        $header = $this->createAuthHeader();
        $data = [
            'status' => Project::PUBLISHED,
        ];

        $response = $this->json('POST', 'projects/'.$project->id.'/publish', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_should_not_allow_project_teacher_to_publish_completed_project()
    {
        $project = factory(Project::class)->create();
        $user = $this->getUser();
        $user->role()->associate(Role::byName('teacher')->first())->save();
        $project->addParticipant('teacher', $user);
        $project->status = Project::COMPLETED;
        $project->save();
        $header = $this->createAuthHeader();
        $data = [
            'status' => Project::PUBLISHED,
        ];

        $response = $this->json('POST', 'projects/'.$project->id.'/publish', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_should_not_allow_global_student_to_publish_project()
    {
        $project = factory(Project::class)->create();
        $user = $this->getUser();
        $user->role()->associate(Role::byName('student')->first())->save();
        $project->status = Project::COMPLETED;
        $project->save();
        $header = $this->createAuthHeader();
        $data = [
            'status' => Project::PUBLISHED,
        ];

        $response = $this->json('POST', 'projects/'.$project->id.'/publish', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_should_allow_global_administrator_to_publish_project()
    {
        $project = factory(Project::class)->create();
        $user = $this->getUser();
        $user->role()->associate(Role::byName('administrator')->first())->save();
        $project->status = Project::COMPLETED;
        $project->save();
        $header = $this->createAuthHeader();
        $data = [
            'status' => Project::PUBLISHED,
        ];

        $response = $this->json('POST', 'projects/'.$project->id.'/publish', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }
}
