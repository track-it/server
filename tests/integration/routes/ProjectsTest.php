<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Trackit\Models\Project;

class ProjectsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_return_a_list_of_projects()
    {
        $projects = factory(Project::class, 3)->create();

        $header = $this->createAuthHeader();
        $response = $this->get('projects/', $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(3, count($jsonObject->data));
    }

    /** @test */
    public function it_should_return_a_project()
    {
        $project = factory(Project::class)->create();

        $header = $this->createAuthHeader();
        $response = $this->get('projects/'.$project->id, $header)->response;

        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($project->id, $jsonObject->data->id);
    }

    /** @test */
    public function it_should_return_a_failure_for_a_non_existing_project()
    {
        $response = $this->get('project/9999999999')->response;

        $this->assertEquals(404, $response->getStatusCode());
    }

    /** @test */
    public function it_should_create_a_new_project()
    {
        $header = $this->createAuthHeader();
        $response = $this->post('projects', ['name' => 'Kebab'], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Kebab', $jsonObject->data->name);
    }

        /** @test */
    public function it_should_update_an_existing_project()
    {
        $header = $this->createAuthHeader();

        $project = factory(Project::class)->create();

        $response = $this->put('projects/'.$project->id, ['name' => 'new'], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('new', $jsonObject->data->name);
    }

    /** @test */
    public function it_should_delete_an_existing_project()
    {      
        $header = $this->createAuthHeader();

        $project = factory(Project::class)->create();

        $response = $this->delete('projects/'.$project->id, [], $header)->response;

        $this->assertEquals(204, $response->getStatusCode());
    }
}