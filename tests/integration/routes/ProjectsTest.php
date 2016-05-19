<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Trackit\Models\Tag;
use Trackit\Models\Team;
use Trackit\Models\User;
use Trackit\Models\Role;
use Trackit\Models\Project;
use Trackit\Models\Proposal;
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
    public function it_should_return_a_filtered_list_of_projects()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('teacher')->first());
        $user->save();
        factory(Project::class, 10)->create(['status' => Project::NOT_COMPLETED]);
        factory(Project::class, 3)->create(['status' => Project::COMPLETED]);
        factory(Project::class, 5)->create(['status' => Project::PUBLISHED]);

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'projects', [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertObjectHasAttribute('data', $jsonObject);
        $this->assertInternalType('array', $jsonObject->data);
        $this->assertEquals(18, $jsonObject->to);
    }

    /** @test */
    public function it_should_return_a_project()
    {
        $project = factory(Project::class)->create();
        $project->status = Project::COMPLETED;
        $project->save();

        $header = $this->createAuthHeader();
        $response = $this->get('projects/'.$project->id, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($project->id, $jsonObject->data->id);
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
        factory(User::class, 5)->create()->each(function ($user) use (&$team) {
            $team->users()->attach($user->id);
        });
        $team->save();
        $data = [
            'title' => 'New Project',
            'team_id' => $team->id,
            'tags' => [
                [
                    'name' => 'tag1',
                ],
                [
                    'name' => 'tag2'
                ],
            ],
        ];

        $header = $this->createAuthHeader();
        $response = $this->json('POST', 'proposals/'.$proposal->id.'/projects', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $participants = $jsonObject->data->participants;

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($data['title'], $jsonObject->data->title);
        $this->assertEquals($proposal->id, $jsonObject->data->proposal_id);
        $this->assertEquals($data['tags'][0]['name'], $jsonObject->data->tags[0]->name);
        $this->assertEquals(Project::NOT_COMPLETED, $jsonObject->data->status);
        $this->assertTrue($this->assertArrayContainsSameObjectWithValue($participants, "id", $user->id));
        foreach ($team->users as $teamUser) {
            $this->assertTrue($this->assertArrayContainsSameObjectWithValue($participants, "id", $teamUser->id));
        }
    }

    /** @test */
    public function it_should_remove_team_when_creating_a_new_project_from_a_proposal()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('teacher')->first())->save();
        $proposal = factory(Proposal::class)->create(['author_id' => $this->getUser()->id]);
        $team = factory(Team::class)->create();
        factory(User::class, 5)->create()->each(function ($user) use (&$team) {
            $team->users()->attach($user->id);
        });
        $team->save();
        $data = [
            'title' => 'New Project',
            'team_id' => $team->id,
            'tags' => [
                [
                    'name' => 'ASD'
                ],
            ],
        ];

        $header = $this->createAuthHeader();
        $response = $this->json('POST', 'proposals/'.$proposal->id.'/projects', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $participants = $jsonObject->data->participants;

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNull(Team::find($team->id));
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
            'tags' => [
                [
                    'name' => 'tag1',
                ],
                [
                    'name' => 'tag2'
                ],
            ],
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
        $project->addParticipant('teacher', $user);
        $header = $this->createAuthHeader();

        $response = $this->put('projects/'.$project->id, ['title' => 'new'], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('new', $jsonObject->data->title);
    }

    /** @test */
    public function it_should_update_an_existing_project_with_new_tags()
    {
        $project = factory(Project::class)->create();
        $user = $this->getUser();
        $user->role()->associate(Role::byName('student')->first())->save();
        $project->addParticipant('teacher', $user);
        $project->tags()->attach(Tag::create(['name' => 'ASD']));
        $header = $this->createAuthHeader();
        $data = [
            'tags' => [
                [
                    'name' => 'ZXC',
                ],
                [
                    'name' => 'QWE',
                ],
            ],
        ];

        $response = $this->json('PUT', 'projects/'.$project->id, $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($this->assertArrayContainsSameObjectWithValue($jsonObject->data->tags, 'name', 'ZXC'));
        $this->assertFalse($this->assertArrayContainsSameObjectWithValue($jsonObject->data->tags, 'name', 'ASD'));
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

    /** @test */
    public function it_should_allow_student_to_publish_project()
    {
        $project = factory(Project::class)->create();
        $user = $this->getUser();
        $user->role()->associate(Role::byName('student')->first())->save();
        $project->addParticipant('student', $user);
        $project->status = Project::COMPLETED;
        $project->save();

        $header = $this->createAuthHeader();
        $response = $this->post('projects/'.$project->id.'/publish', ['publish' => true], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(Project::PUBLISHED, $jsonObject->data->status);
    }

    /** @test */
    public function it_should_allow_student_to_unpublish_project()
    {
        $project = factory(Project::class)->create();
        $user = $this->getUser();
        $user->role()->associate(Role::byName('student')->first())->save();
        $project->addParticipant('student', $user);
        $project->status = Project::PUBLISHED;
        $project->save();

        $header = $this->createAuthHeader();
        $response = $this->post('projects/'.$project->id.'/publish', ['publish' => false], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(Project::COMPLETED, $jsonObject->data->status);
    }

    /** @test */
    public function it_should_return_a_collection_of_projects_matching_search_criteria()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('teacher')->first());
        $user->save();
        factory(Project::class, 10)->create(['title' => 'First project', 'status' => Project::NOT_COMPLETED]);
        factory(Project::class, 3)->create(['title' => 'Java project', 'status' => Project::COMPLETED]);
        factory(Project::class, 5)->create(['title' => 'Third project', 'status' => Project::PUBLISHED]);

        $project = Project::all()->first();
        $project->tags()->attach(factory(Tag::class)->create(['name' => 'Java']));

        $searchterm = 'java';
        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'projects?search='.$searchterm, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertObjectHasAttribute('data', $jsonObject);
        $this->assertInternalType('array', $jsonObject->data);
        $this->assertEquals(4, $jsonObject->to);
    }
}
