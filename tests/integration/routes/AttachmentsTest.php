<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Trackit\Models\Attachment;
use Trackit\Models\Proposal;
use Trackit\Models\Project;

class AttachmentsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_upload_an_attached_file_to_a_proposal()
    {
        $proposal = factory(Proposal::class)->create(['author_id' => $this->getUser()->id]);
        $file = new UploadedFile(
            base_path('tests/files/test.txt'),
            'test.txt',
            'text/plain',
            20,
            null,
            false
        );

        $header = $this->createAuthHeader();
        $response = $this->call(
            'POST',
            'proposals/'.$proposal->id.'/attachments',
            [], // parameters
            [], // cookies
            ['files' => [$file]], // files
            $header // server
        );

        $jsonObject = json_decode($response->getContent());
        $this->assertEquals($file->getClientOriginalName(), $jsonObject->data[0]->title);
    }

    /** @test */
    public function it_should_upload_an_attached_file_to_a_project()
    {
        $project = factory(Project::class)->create();
        $file = new UploadedFile(
            base_path('tests/files/test.txt'),
            'test.txt',
            'text/plain',
            20,
            null,
            false
        );

        $header = $this->createAuthHeader();
        $response = $this->call(
            'POST',
            'projects/'.$project->id.'/attachments',
            [], // parameters
            [], // cookies
            ['files' => [$file]], // files
            $header // server
        );

        $jsonObject = json_decode($response->getContent());
        $this->assertEquals($file->getClientOriginalName(), $jsonObject->data[0]->title);
    }

    /** @test */
    public function it_should_return_an_existing_attachment()
    {
        $attachment = factory(Attachment::class)->create();

        $header = $this->createAuthHeader();
        $response = $this->get('attachments/'.$attachment->id, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($attachment->id, $jsonObject->data->id);
    }

    /** @test */
    public function it_should_delete_an_existing_attachment()
    {
        $attachment = factory(Attachment::class)->create();

        $header = $this->createAuthHeader();
        $response = $this->delete('attachments/'.$attachment->id, [], $header)->response;

        $this->assertEquals(204, $response->getStatusCode());
    }

    /** @test */
    public function it_should_download_an_attached_file()
    {
        $attachment = factory(Attachment::class)->create();
        $attachment->url = 'attachments/'.$attachment->id.'/test.txt';
        $content = file_get_contents(getcwd() . '/tests/files/test.txt');
        Storage::put($attachment->url, $content);
        $attachment->save();

        $header = $this->createAuthHeader();
        $response = $this->get('attachments/'.$attachment->id.'/download', $header)->response;

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($content, $response->getContent());
    }

    /** @test */
    public function it_should_return_all_attachments_for_a_proposal()
    {
        $proposal = factory(Proposal::class)->create();
        $attachment = factory(Attachment::class)->create();
        $attachment2 = factory(Attachment::class)->create();
        $proposal->attachments()->save($attachment);
        $proposal->attachments()->save($attachment2);

        $header = $this->createAuthHeader();
        $response = $this->get('proposals/'.$proposal->id.'/attachments/', $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(2, count($jsonObject->data));
    }

    /** @test */
    public function it_should_return_all_attachments_for_a_project()
    {
        $project = factory(Project::class)->create();
        $attachment = factory(Attachment::class)->create();
        $attachment2 = factory(Attachment::class)->create();
        $project->attachments()->save($attachment);
        $project->attachments()->save($attachment2);

        $header = $this->createAuthHeader();
        $response = $this->get('projects/'.$project->id.'/attachments/', $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(2, count($jsonObject->data));
    }
}
