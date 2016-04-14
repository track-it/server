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
        // $role = Role::byName('customer')->first();
        $proposal = factory(Proposal::class)->create(['title' => 'asdasdasdasd']);
        
        $hasStatus = $proposal->status > 0;

        $this->assertTrue($hasStatus);
    }
    /** @test */
    public function it_has_a_creator()
    {
        // Given
        // $role = Role::byName('customer')->first();
        $proposal = factory(Proposal::class)->create(['title' => 'asdasdasdasd']);
        
        $hasCreator = !! $proposal->creator();

        $this->assertTrue($hasCreator);
    }
}