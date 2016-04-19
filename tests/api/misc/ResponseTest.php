<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ResponseTest extends TestCase
{
    /** @test */
    public function it_has_a_accept_control_allow_origin_header()
    {
    	 $response = $this->visit('/');
         dd(this->header());     
				 
    }
}
