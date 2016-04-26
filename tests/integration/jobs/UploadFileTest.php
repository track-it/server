<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Carbon\Carbon;

use Trackit\Models\Attachment;
use Trackit\Jobs\UploadFile;

class UploadFileTest extends TestCase
{
    use DatabaseTransactions, DispatchesJobs;

    /** @test */
    public function it_should_upload_attached_file_to_storage()
    {
        $attachment = factory(Trackit\Models\Attachment::class)->create(['title' => 'test.txt']);

        $uploadedFile = new UploadedFile(
            base_path('tests/files/test.txt'),
            'test.txt',
            'text/plain',
            20,
            null,
            false
        );

        $job = new UploadFile($attachment, ['file' => $uploadedFile->getRealPath()]);

        $this->dispatch($job);
        
        $this->assertTrue(Storage::exists('attachments/'.$attachment->id.'/'.$attachment->title));
    }
}
