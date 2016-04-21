<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Trackit\Models\Attachment;
use Trackit\Models\Proposal;

class AttachmentsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function is_should_return_an_existing_attachment()
    {
        $attachment = factory(Attachment::class)->create();
        $proposal = factory(Proposal::class)->create();
        $proposal->attachments()->save($attachment);
        $proposal->save();

        $response = $this->get('proposals/'.$proposal->id.'/attachments/'.$attachment->id)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals($attachment->id, $jsonObject->items[0]->id);
    }

    /** @test */
    public function it_should_upload_an_attached_file()
    {
        $file = new UploadedFile(
            base_path('tests/files/test.txt'), 
            'test.txt',
            'text/plain',
            20,
            null,
            false
        );

        $proposal = factory(Proposal::class)->create();

        $response = $this->call(
            'POST',
            'proposals/'.$proposal->id.'/attachments',
            [],
            [],
            [$file]
        );

        $jsonObject = json_decode($response->getContent());

        $this->assertEquals($file->getClientOriginalName(), $jsonObject->items[0]->title);
    }
}
