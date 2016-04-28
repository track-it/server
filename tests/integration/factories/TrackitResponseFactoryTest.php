<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Trackit\Models\Proposal;
use Trackit\Support\TrackitResponseFactory;

class TrackitResponseFactoryTest extends TestCase
{
    use DatabaseTransactions;
    
    /** @test */
    public function it_should_create_a_formatted_response_from_a_single_model()
    {
        $proposal = factory(Proposal::class)->create();
        $viewFactory = Mockery::mock(Illuminate\Contracts\View\Factory::class);
        $redirector = Mockery::mock(Illuminate\Routing\Redirector::class);
        $responseFactory = new TrackitResponseFactory($viewFactory, $redirector);

        $response = $responseFactory->json($proposal);
        $jsonObject = json_decode($response->getContent());

        $this->assertObjectHasAttribute('data', $jsonObject);
        $this->assertTrue(is_object($jsonObject->data));
    }

    /** @test */
    public function it_should_create_a_formatted_response_from_a_collection()
    {
        factory(Proposal::class, 5)->create();
        $collection = Proposal::all();
        $viewFactory = Mockery::mock(Illuminate\Contracts\View\Factory::class);
        $redirector = Mockery::mock(Illuminate\Routing\Redirector::class);
        $responseFactory = new TrackitResponseFactory($viewFactory, $redirector);

        $response = $responseFactory->json($collection);
        $jsonObject = json_decode($response->getContent());

        $this->assertObjectHasAttribute('data', $jsonObject);
        $this->assertTrue(is_array($jsonObject->data));
    }

    /** @test */
    public function it_should_create_a_formatted_response_from_a_paginated_collection()
    {
        factory(Proposal::class, 5)->create();
        $collection = Proposal::paginate(10);
        $viewFactory = Mockery::mock(Illuminate\Contracts\View\Factory::class);
        $redirector = Mockery::mock(Illuminate\Routing\Redirector::class);
        $responseFactory = new TrackitResponseFactory($viewFactory, $redirector);

        $response = $responseFactory->json($collection);
        $jsonObject = json_decode($response->getContent());

        $this->assertObjectHasAttribute('data', $jsonObject);
        $this->assertTrue(is_array($jsonObject->data));
    }

}