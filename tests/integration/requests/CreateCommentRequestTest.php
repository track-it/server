<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Trackit\Models\Proposal;
use Trackit\Models\Comment;
use Trackit\Models\Role;
use Trackit\Models\Project;

class CreateCommentRequestTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_allow_author_to_comment_on_its_proposal()
    {
        $header = $this->createAuthHeader();
        $proposal = factory(Proposal::class)->create(['author_id' => $this->getUser()->id]);
        $data = [
            'body' => str_random(500),
        ];

        $response = $this->json('POST', 'proposals/' . $proposal->id . '/comments', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_disallow_student_to_comment_on_others_proposals()
    {
        $header = $this->createAuthHeader();
        $user = $this->getUser();
        $user->role()->associate(Role::byName('student')->first())->save();
        $proposal = factory(Proposal::class)->create();
        $data = [
            'body' => str_random(500),
        ];

        $response = $this->json('POST', 'proposals/' . $proposal->id . '/comments', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_should_disallow_customer_to_comment_on_others_proposals()
    {
        $header = $this->createAuthHeader();
        $user = $this->getUser();
        $user->role()->associate(Role::byName('customer')->first())->save();
        $proposal = factory(Proposal::class)->create();
        $data = [
            'body' => str_random(500),
        ];

        $response = $this->json('POST', 'proposals/' . $proposal->id . '/comments', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_should_allow_teacher_to_comment_on_proposals()
    {
        $header = $this->createAuthHeader();
        $proposal = factory(Proposal::class)->create();
        $comment = factory(Comment::class)->create();
        $data = [
            'body' => str_random(500),
        ];

        $response = $this->json('POST', 'proposals/' . $proposal->id . '/comments', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_allow_administrator_to_comment_on_proposals()
    {
        $header = $this->createAuthHeader();
        $user = $this->getUser();
        $user->role()->associate(Role::byName('administrator')->first())->save();
        $proposal = factory(Proposal::class)->create();
        $data = [
            'body' => str_random(500),
        ];

        $response = $this->json('POST', 'proposals/' . $proposal->id . '/comments', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_allow_administrator_to_comment_on_projects()
    {
        $header = $this->createAuthHeader();
        $user = $this->getUser();
        $user->role()->associate(Role::byName('administrator')->first())->save();
        $project = factory(Project::class)->create();
        $data = [
            'body' => str_random(500),
        ];

        $response = $this->json('POST', 'projects/' . $project->id . '/comments', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_not_allow_a_body_longer_than_5000_characters()
    {
        $header = $this->createAuthHeader();
        $proposal = factory(Proposal::class)->create(['author_id' => $this->getUser()->id]);
        $data = [
            'body' => str_random(5001),
        ];

        $response = $this->json('POST', 'proposals/' . $proposal->id . '/comments', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The body may not be greater than 5000 characters.', $jsonObject->body[0]);
    }

    /** @test */
    public function it_should_not_allow_a_missing_body()
    {
        $header = $this->createAuthHeader();
        $proposal = factory(Proposal::class)->create(['author_id' => $this->getUser()->id]);
        $data = [
        ];

        $response = $this->json('POST', 'proposals/' . $proposal->id . '/comments', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('The body field is required.', $jsonObject->body[0]);
    }
}
