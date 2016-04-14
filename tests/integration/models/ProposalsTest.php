<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Trackit\Models\Proposal;

class ProposalsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_has_a_status()
    {
        // Given
        $proposal = factory(Proposal::class)->create();
        
        $hasStatus = in_array($proposal->status, Proposal::STATUSES);

        $this->assertTrue($hasStatus);
    }

    /** @test */
    public function it_has_a_creator()
    {
        // Given
        // $role = Role::byName('customer')->first();
        $proposal = factory(Proposal::class)->create();
        
        $hasCreator = !! $proposal->creator();

        $this->assertTrue($hasCreator);
    }
}
