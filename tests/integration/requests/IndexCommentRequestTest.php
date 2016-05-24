<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Trackit\Models\Proposal;
use Trackit\Models\Comment;
use Trackit\Models\Role;
use Trackit\Models\Project;

class IndexCommentRequestTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_allow_return_of_comments_for_author_of_proposal()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('student')->first())->save();
        $header = $this->createAuthHeader();
        $proposal = factory(Proposal::class)->create(['author_id' => $this->getUser()->id]);
        factory(Comment::class, 5)->create()->each(function ($comment) use (&$proposal) {
            $proposal->comments()->save($comment);
        });
        $proposal->save();

        $response = $this->json('GET', 'proposals/' . $proposal->id . '/comments', [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_disallow_return_of_proposal_comments_for_student()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('student')->first())->save();
        $header = $this->createAuthHeader();
        $proposal = factory(Proposal::class)->create();
        factory(Comment::class, 5)->create()->each(function ($comment) use (&$proposal) {
            $proposal->comments()->save($comment);
        });
        $proposal->save();

        $response = $this->json('GET', 'proposals/' . $proposal->id . '/comments', [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_should_allow_return_of_proposal_comments_for_teacher()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('teacher')->first())->save();
        $header = $this->createAuthHeader();
        $proposal = factory(Proposal::class)->create();
        factory(Comment::class, 5)->create()->each(function ($comment) use (&$proposal) {
            $proposal->comments()->save($comment);
        });
        $proposal->save();

        $response = $this->json('GET', 'proposals/' . $proposal->id . '/comments', [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_allow_return_of_proposal_comments_for_administrator()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('administrator')->first())->save();
        $header = $this->createAuthHeader();
        $proposal = factory(Proposal::class)->create();
        factory(Comment::class, 5)->create()->each(function ($comment) use (&$proposal) {
            $proposal->comments()->save($comment);
        });
        $proposal->save();

        $response = $this->json('GET', 'proposals/' . $proposal->id . '/comments', [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_allow_return_of_comments_for_project_participant()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('student')->first())->save();
        $header = $this->createAuthHeader();
        $project = factory(Project::class)->create();
        factory(Comment::class, 5)->create()->each(function ($comment) use (&$project) {
            $project->comments()->save($comment);
        });
        $project->save();
        $project->addParticipant('student', $user);

        $response = $this->json('GET', 'projects/' . $project->id . '/comments', [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_disallow_return_of_project_comments_for_student()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('student')->first())->save();
        $header = $this->createAuthHeader();
        $project = factory(Project::class)->create();
        factory(Comment::class, 5)->create()->each(function ($comment) use (&$project) {
            $project->comments()->save($comment);
        });
        $project->save();

        $response = $this->json('GET', 'projects/' . $project->id . '/comments', [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_should_disallow_return_of_project_comments_for_teacher()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('teacher')->first())->save();
        $header = $this->createAuthHeader();
        $project = factory(Project::class)->create();
        factory(Comment::class, 5)->create()->each(function ($comment) use (&$project) {
            $project->comments()->save($comment);
        });
        $project->save();

        $response = $this->json('GET', 'projects/' . $project->id . '/comments', [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_should_allow_return_of_project_comments_for_administrator()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('administrator')->first())->save();
        $header = $this->createAuthHeader();
        $project = factory(Project::class)->create();
        factory(Comment::class, 5)->create()->each(function ($comment) use (&$project) {
            $project->comments()->save($comment);
        });
        $project->save();

        $response = $this->json('GET', 'projects/' . $project->id . '/comments', [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }
}
