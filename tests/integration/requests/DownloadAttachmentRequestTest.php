<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Trackit\Models\User;
use Trackit\Models\Proposal;
use Trackit\Models\Project;
use Trackit\Models\Attachment;
use Trackit\Models\Role;

class DownloadAttachmentRequestTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_allow_guest_to_download_approved_proposal_attachment()
    {
        $proposal = factory(Proposal::class)->create();
        $proposal->status = Proposal::APPROVED;
        $attachment = factory(Attachment::class)->create(['path' => '.gitignore']);
        $proposal->attachments()->save($attachment);
        $proposal->save();

        $response = $this->json('GET', 'attachments/'.$attachment->id, [])->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_disallow_guest_to_download_unapproved_proposal_attachment()
    {
        $proposal = factory(Proposal::class)->create();
        $proposal->status = Proposal::NOT_APPROVED;
        $attachment = factory(Attachment::class)->create(['path' => '.gitignore']);
        $proposal->attachments()->save($attachment);
        $proposal->save();

        $response = $this->json('GET', 'attachments/'.$attachment->id, [])->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_should_allow_guest_to_download_published_project_attachment()
    {
        $project = factory(Project::class)->create();
        $project->status = Project::PUBLISHED;
        $attachment = factory(Attachment::class)->create(['path' => '.gitignore']);
        $project->attachments()->save($attachment);
        $project->save();

        $response = $this->json('GET', 'attachments/'.$attachment->id, [])->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_disallow_guest_to_download_uncompleted_project_attachment()
    {
        $project = factory(Project::class)->create();
        $project->status = Project::NOT_COMPLETED;
        $attachment = factory(Attachment::class)->create(['path' => '.gitignore']);
        $project->attachments()->save($attachment);
        $project->save();

        $response = $this->json('GET', 'attachments/'.$attachment->id, [])->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_should_allow_student_to_download_own_proposal_attachment()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('student')->first())->save();
        $proposal = factory(Proposal::class)->create(['author_id' => $user->id]);
        $proposal->status = Proposal::NOT_APPROVED;
        $attachment = factory(Attachment::class)->create(['path' => '.gitignore']);
        $proposal->attachments()->save($attachment);
        $proposal->save();

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'attachments/'.$attachment->id, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_allow_student_to_download_approved_proposal_attachment()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('student')->first())->save();
        $proposal = factory(Proposal::class)->create();
        $proposal->status = Proposal::APPROVED;
        $attachment = factory(Attachment::class)->create(['path' => '.gitignore']);
        $proposal->attachments()->save($attachment);
        $proposal->save();

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'attachments/'.$attachment->id, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_disallow_student_to_download_unapproved_proposal_attachment()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('student')->first())->save();
        $proposal = factory(Proposal::class)->create();
        $proposal->status = Proposal::NOT_APPROVED;
        $attachment = factory(Attachment::class)->create(['path' => '.gitignore']);
        $proposal->attachments()->save($attachment);
        $proposal->save();

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'attachments/'.$attachment->id, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_should_allow_student_to_download_own_project_attachment()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('student')->first())->save();
        $project = factory(Project::class)->create();
        $project->status = Project::NOT_COMPLETED;
        $attachment = factory(Attachment::class)->create(['path' => '.gitignore']);
        $project->attachments()->save($attachment);
        $project->addParticipant('student', $user);
        $project->save();

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'attachments/'.$attachment->id, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_allow_student_to_download_published_project_attachment()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('student')->first())->save();
        $project = factory(Project::class)->create();
        $project->status = Project::PUBLISHED;
        $attachment = factory(Attachment::class)->create(['path' => '.gitignore']);
        $project->attachments()->save($attachment);
        $project->save();

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'attachments/'.$attachment->id, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_disallow_student_to_download_uncompleted_project_attachment()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('student')->first())->save();
        $project = factory(Project::class)->create();
        $project->status = Project::NOT_COMPLETED;
        $attachment = factory(Attachment::class)->create(['path' => '.gitignore']);
        $project->attachments()->save($attachment);
        $project->save();

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'attachments/'.$attachment->id, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_should_allow_customer_to_download_own_proposal_attachment()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('customer')->first())->save();
        $proposal = factory(Proposal::class)->create(['author_id' => $user->id]);
        $proposal->status = Proposal::NOT_APPROVED;
        $attachment = factory(Attachment::class)->create(['path' => '.gitignore']);
        $proposal->attachments()->save($attachment);
        $proposal->save();

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'attachments/'.$attachment->id, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_allow_customer_to_download_approved_proposal_attachment()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('customer')->first())->save();
        $proposal = factory(Proposal::class)->create();
        $proposal->status = Proposal::APPROVED;
        $attachment = factory(Attachment::class)->create(['path' => '.gitignore']);
        $proposal->attachments()->save($attachment);
        $proposal->save();

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'attachments/'.$attachment->id, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_disallow_customer_to_download_unapproved_proposal_attachment()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('customer')->first())->save();
        $proposal = factory(Proposal::class)->create();
        $proposal->status = Proposal::NOT_APPROVED;
        $attachment = factory(Attachment::class)->create(['path' => '.gitignore']);
        $proposal->attachments()->save($attachment);
        $proposal->save();

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'attachments/'.$attachment->id, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_should_allow_teacher_to_download_own_proposal_attachment()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('teacher')->first())->save();
        $proposal = factory(Proposal::class)->create(['author_id' => $user->id]);
        $proposal->status = Proposal::NOT_APPROVED;
        $attachment = factory(Attachment::class)->create(['path' => '.gitignore']);
        $proposal->attachments()->save($attachment);
        $proposal->save();

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'attachments/'.$attachment->id, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_allow_teacher_to_download_approved_proposal_attachment()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('teacher')->first())->save();
        $proposal = factory(Proposal::class)->create();
        $proposal->status = Proposal::APPROVED;
        $attachment = factory(Attachment::class)->create(['path' => '.gitignore']);
        $proposal->attachments()->save($attachment);
        $proposal->save();

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'attachments/'.$attachment->id, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_allow_teacher_to_download_unapproved_proposal_attachment()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('teacher')->first())->save();
        $proposal = factory(Proposal::class)->create();
        $proposal->status = Proposal::NOT_APPROVED;
        $attachment = factory(Attachment::class)->create(['path' => '.gitignore']);
        $proposal->attachments()->save($attachment);
        $proposal->save();

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'attachments/'.$attachment->id, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_allow_teacher_to_download_own_project_attachment()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('teacher')->first())->save();
        $project = factory(Project::class)->create();
        $project->status = Project::NOT_COMPLETED;
        $attachment = factory(Attachment::class)->create(['path' => '.gitignore']);
        $project->attachments()->save($attachment);
        $project->addParticipant('teacher', $user);
        $project->save();

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'attachments/'.$attachment->id, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_allow_teacher_to_download_published_project_attachment()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('teacher')->first())->save();
        $project = factory(Project::class)->create();
        $project->status = Project::PUBLISHED;
        $attachment = factory(Attachment::class)->create(['path' => '.gitignore']);
        $project->attachments()->save($attachment);
        $project->save();

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'attachments/'.$attachment->id, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_allow_teacher_to_download_uncompleted_project_attachment()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('teacher')->first())->save();
        $project = factory(Project::class)->create();
        $project->status = Project::NOT_COMPLETED;
        $attachment = factory(Attachment::class)->create(['path' => '.gitignore']);
        $project->attachments()->save($attachment);
        $project->save();

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'attachments/'.$attachment->id, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

}
