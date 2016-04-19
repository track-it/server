<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ResponseTest extends TestCase
{
	
    /** @test */
    public function it_has_a_access_control_allow_origin_header()
    {
		$response = $this->get('/', [ 'Origin' => 'http://test.com' ])->response;

		$header = !! $response->headers->get('Access-Control-Allow-Origin');

		$this->assertTrue($header);
    }

}
