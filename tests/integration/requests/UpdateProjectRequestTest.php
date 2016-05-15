<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Trackit\Models\Role;
use Trackit\Models\Project;
use Trackit\Models\Team;
use Trackit\Models\User;

class UpdateProjectRequestTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_allow_teacher_user_with_teacher_role_to_update_project()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('teacher')->first())->save();
        $project = factory(Project::class)->create();
        $project->addParticipant('teacher', $user);
        $data = [
            'title' => 'New Project',
        ];

        $header = $this->createAuthHeader();
        $response = $this->json('PUT', 'projects/'.$project->id, $data, $header)->response;

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_allow_global_users_with_administrator_role_to_update_project()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('administrator')->first())->save();
        $project = factory(Project::class)->create();
        $data = [
            'title' => 'New Project',
        ];

        $header = $this->createAuthHeader();
        $response = $this->json('PUT', 'projects/'.$project->id, $data, $header)->response;

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_allow_student_user_with_teacher_project_role_to_update_project()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('student')->first())->save();
        $project = factory(Project::class)->create();
        $project->addParticipant('teacher', $user);
        $data = [
            'title' => 'New Project',
        ];

        $header = $this->createAuthHeader();
        $response = $this->json('PUT', 'projects/'.$project->id, $data, $header)->response;

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_return_error_when_users_not_in_project_try_to_update_project()
    {
        $user = $this->getUser();
        $project = factory(Project::class)->create();
        $data = [
            'title' => 'New Project',
        ];

        $header = $this->createAuthHeader();
        $response = $this->json('PUT', 'projects/'.$project->id, $data, $header)->response;

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_should_return_error_when_global_users_without_administrator_role_try_to_update_project()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('student')->first())->save();
        $project = factory(Project::class)->create();
        $data = [
            'title' => 'New Project',
        ];

        $header = $this->createAuthHeader();
        $response = $this->json('PUT', 'projects/'.$project->id, $data, $header)->response;

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_should_return_error_when_guests_try_to_update_project()
    {
        $project = factory(Project::class)->create();
        $data = [
            'title' => 'New Project',
        ];

        $response = $this->json('PUT', 'projects/'.$project->id, $data)->response;

        $this->assertEquals(401, $response->getStatusCode());
    }
}
