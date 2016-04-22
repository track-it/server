<?php

namespace Trackit\Jobs;

use Trackit\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Storage;
use Log;

use Trackit\Models\Attachment;

class UploadFile extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var
     */
    protected $attachment;

    /**
     * @var
     */
    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Attachment $attachment, $data)
    {
        $this->attachment = $attachment;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = file_get_contents($this->data['file']);

        Storage::put('attachments/'.$this->attachment->id.'/'.$this->attachment->title, $data);
    }
}
