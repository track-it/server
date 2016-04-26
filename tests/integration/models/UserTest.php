<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
	use DatabaseTransactions;

    /** @test */
    public function has_an_api_token()
    {
        $user = factory(Trackit\Models\User::class)->create();

        $hasApiToken = !! $user->api_token;

        $this->assertTrue($hasApiToken);
    }

    /** @test */
    public function can_own_a_proposal()
    {
    	$proposal = factory(Trackit\Models\Proposal::class)->create();

    	$user = factory(Trackit\Models\User::class)->create();
    	$user->proposals()->save($proposal);

    	$this->assertEquals($proposal->id, $user->proposals->first()->id);
    }

    /** @test */
    public function has_a_role()
    {
    	$role = factory(Trackit\Models\Role::class)->create();

    	$user = factory(Trackit\Models\User::class)->create();
    	$user->role()->associate($role);

    	$this->assertEquals($role->name, $user->role->name);
    }
}
