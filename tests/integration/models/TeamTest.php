<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Collection;
use Trackit\Models\User;
use Trackit\Models\Team;

class TeamTest extends TestCase
{
	use DatabaseTransactions;

	private $team;
	private $users;

	/** @test */
	public function team_contains_users()
	{
		$this->setUpTeam();
		$this->assertEquals(3, $this->team->users->count());
	}
	
	/** @test */
	public function user_can_be_added_to_team()
	{
		$user = factory(User::class)->create();
		$this->team = Team::create();
		$user->joinTeam($this->team);
		$this->assertEquals($user->id, $this->team->users->first()->id);
	}

	/** @test */
	public function user_can_be_removed_from_team()
	{
		$this->setUpTeam();
		$this->users[0]->leaveTeam($this->team);
		$this->assertEquals(2, $this->team->fresh()->users->count());
	}	

	/** @test */
	public function team_will_be_deleted_when_last_user_is_removed()
	{
		$this->setUpTeam();
		$team = $this->team;
		$this->users->each(function ($user) use ($team) {
			$user->leaveTeam($this->team);
		});
		$this->assertEquals(Team::get([$this->team->id => 'id'])->count(), 0);
	}

	private function setUpTeam()
	{
		$this->users = new Collection(factory(User::class, 3)->create());
		$this->team = Team::create();
		$team = $this->team;
		$this->users->each(function ($user) use ($team) {
			$user->joinTeam($this->team);
		});
	}
}
