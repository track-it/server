<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Trackit\Models\Project;
use Trackit\Models\Proposal;
use Trackit\Models\Team;
use Trackit\Models\User;
use Trackit\Models\Role;
use Trackit\Models\Attachment;

class ProjectsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_return_a_list_of_projects_for_a_proposal()
    {
        $proposal = factory(Proposal::class)->create();
        factory(Project::class, 3)->create(['proposal_id' => $proposal->id]);

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'proposals/'.$proposal->id.'/projects/', [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(3, count($jsonObject->data));
    }

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
        $project->status = Project::COMPLETED;
        $team = factory(Team::class)->create();
        factory(User::class, 5)->create()->each(function ($user) use ($team) {
            $team->users()->attach($user->id);
        });
        $project->team()->associate($team);
        $project->save();

        $header = $this->createAuthHeader();
        $response = $this->get('projects/'.$project->id, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($project->id, $jsonObject->data->id);
        $this->assertEquals($project->team->id, $jsonObject->data->team->id);
        $this->assertEquals(5, count($jsonObject->data->team->users));
    }

    /** @test */
    public function it_should_return_a_project_with_attachments()
    {
        $project = factory(Project::class)->create();
        $project->status = Project::COMPLETED;
        $attachment = factory(Attachment::class)->create();
        $attachment2 = factory(Attachment::class)->create();
        $project->attachments()->save($attachment);
        $project->attachments()->save($attachment2);
        $project->save();

        $header = $this->createAuthHeader();
        $response = $this->get('projects/'.$project->id, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(2, count($jsonObject->data->attachments));
    }

    /** @test */
    public function it_should_return_a_failure_for_a_non_existing_project()
    {
        $response = $this->get('project/9999999999')->response;

        $this->assertEquals(404, $response->getStatusCode());
    }

    /** @test */
    public function it_should_create_a_new_project_from_a_proposal()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('teacher')->first())->save();
        $proposal = factory(Proposal::class)->create(['author_id' => $this->getUser()->id]);
        $team = factory(Team::class)->create();
        factory(User::class, 5)->create()->each(function ($user) use ($team) {
            $team->users()->attach($user->id);
        });
        $data = [
            'title' => 'New Project',
            'team_id' => $team->id,
            'tags' => ['tag1', 'tag2'],
        ];

        $header = $this->createAuthHeader();
        $response = $this->json('POST', 'proposals/'.$proposal->id.'/projects', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($data['title'], $jsonObject->data->title);
        $this->assertEquals($data['team_id'], $jsonObject->data->team_id);
        $this->assertEquals($proposal->id, $jsonObject->data->proposal_id);
        $this->assertEquals($data['tags'][0], $jsonObject->data->tags[0]->name);
    }

    /** @test */
    public function it_should_return_error_when_non_teacher_creates_new_project_from_a_proposal()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('student')->first())->save();
        $proposal = factory(Proposal::class)->create(['author_id' => $this->getUser()->id]);
        $team = factory(Team::class)->create();
        factory(User::class, 5)->create()->each(function ($user) use ($team) {
            $team->users()->attach($user->id);
        });
        $data = [
            'name' => 'New Project',
            'team_id' => $team->id,
            'tag_ids' => ['tag1', 'tag2'],
        ];

        $header = $this->createAuthHeader();
        $response = $this->json('POST', 'proposals/'.$proposal->id.'/projects', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_should_update_an_existing_project()
    {
        $project = factory(Project::class)->create();
        $user = $this->getUser();
        $user->role()->associate(Role::byName('student')->first())->save();
        $project->addProjectUser('teacher', $user);
        $header = $this->createAuthHeader();

        $response = $this->put('projects/'.$project->id, ['title' => 'new'], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('new', $jsonObject->data->title);
    }

    /** @test */
    public function it_should_delete_an_existing_project()
    {
        $header = $this->createAuthHeader();
        $user = $this->getUser();
        $user->role()->associate(Role::byName('administrator')->first())->save();

        $project = factory(Project::class)->create();

        $response = $this->delete('projects/'.$project->id, [], $header)->response;

        $this->assertEquals(204, $response->getStatusCode());
    }
}
