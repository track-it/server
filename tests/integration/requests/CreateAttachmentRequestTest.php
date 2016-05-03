<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Trackit\Models\Proposal;
use Trackit\Models\Attachment;

class CreateAttachmentRequestTest extends TestCase
{
    use DatabaseTransactions;

    // Tests not working yet, need rework.

    /** @test */
    // public function it_should_not_allow_an_attachment_larger_than_10mb()
    // {
    //     $proposal = factory(Proposal::class)->create();
    //     $file = new UploadedFile(
    //         base_path('tests/files/test.txt'), 
    //         'test.txt',
    //         'text/plain',
    //         50000,
    //         null,
    //         false
    //     );
    //     $header = $this->createAuthHeader();
    //     // $header['HTTP_ACCEPT'] = 'application/json';
    //     $header['CONTENT_TYPE'] = 'multipart/form-data';
    //     // dd($header);

    //     $response = $this->call(
    //         'POST',
    //         'proposals/'.$proposal->id.'/attachments',
    //         [], // parameters
    //         [], // cookies
    //         ['file' => $file], // files
    //         $header // server
    //     );
    //     dd($response);
    //     $jsonObject = json_decode($response->getContent());
    //     // dd($jsonObject);

    //     $this->assertEquals(422, $response->getStatusCode());
    //     $this->assertEquals('The files.0 may not be greater than 10000 kilobytes.', $jsonObject->{'files.0'}[0]);
    // }

    /** @test */
    // public function it_should_allow_an_attachment_less_than_10mb()
    // {
    //     $proposal = factory(Proposal::class)->create();
    //     $file = new UploadedFile(
    //         base_path('tests/files/5mb.txt'), 
    //         '5mb.txt',
    //         'text/plain',
    //         20,
    //         null,
    //         false
    //     );
    //     $header = $this->createAuthHeader();
    //     $header['HTTP_ACCEPT'] = 'application/json';

    //     $response = $this->call(
    //         'POST',
    //         'proposals/'.$proposal->id.'/attachments',
    //         [], // parameters
    //         [], // cookies
    //         ['file' => $file], // files
    //         $header // server
    //     );
    //     $jsonObject = json_decode($response->getContent());

    //     $this->assertEquals(201, $response->getStatusCode());
    // }
}
