<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Trackit\Models\Role;
use Trackit\Models\Proposal;
use Trackit\Models\User;

class CreateTeamRequestTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_allow_users_with_student_role_to_apply_for_proposal()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('student')->first())->save();
        $proposal = factory(Proposal::class)->create();
        $data = [
            'user_ids' => [],
        ];
        $users = factory(User::class, 5)->create();
        foreach ($users as $user) {
            $data['user_ids'][] = $user->id;
        }

        $header = $this->createAuthHeader();
        $response = $this->json('POST', 'proposals/'.$proposal->id.'/teams', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_should_return_error_when_users_without_student_role_apply_for_proposal()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('customer')->first())->save();
        $proposal = factory(Proposal::class)->create();
        $data = [
            'user_ids' => [],
        ];
        $users = factory(User::class, 5)->create();
        foreach ($users as $user) {
            $data['user_ids'][] = $user->id;
        }

        $header = $this->createAuthHeader();
        $response = $this->json('POST', 'proposals/'.$proposal->id.'/teams', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(403, $response->getStatusCode());
    }
}
