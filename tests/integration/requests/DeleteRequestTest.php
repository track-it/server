<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Trackit\Models\Role;
use Trackit\Models\Proposal;
use Trackit\Models\Project;

class DeleteRequestTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_allow_users_with_administrator_role_to_delete_proposal()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('administrator')->first())->save();
        $proposal = factory(Proposal::class)->create();

        $header = $this->createAuthHeader();
        $response = $this->json('DELETE', 'proposals/'.$proposal->id, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(204, $response->getStatusCode());
    }

    /** @test */
    public function it_should_allow_users_with_administrator_role_to_delete_project()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('administrator')->first())->save();
        $project = factory(Project::class)->create();

        $header = $this->createAuthHeader();
        $response = $this->json('DELETE', 'projects/'.$project->id, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(204, $response->getStatusCode());
    }

    /** @test */
    public function it_should_return_error_when_users_without_administrator_role_delete_project()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('student')->first())->save();
        $project = factory(Project::class)->create();

        $header = $this->createAuthHeader();
        $response = $this->json('DELETE', 'projects/'.$project->id, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(403, $response->getStatusCode());
    }
}
