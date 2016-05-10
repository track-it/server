<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Trackit\Models\Role;

class RoleTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_has_a_set_of_permissions()
    {
        $role = Role::byName('customer')->first();
        
        $canSubmit = $role->can('proposal:submit');
        $canView = $role->can('proposal:view');

        $this->assertTrue($canSubmit);
        $this->assertTrue($canView);
    }

    /** @test */
    public function it_can_be_given_a_permission()
    {
        $role = Role::byName('customer')->first();
        $this->assertFalse($role->can('proposal:categorize')); //We need to make sure the permission is not there initially
        $role->givePermissionTo('proposal:categorize');
        $this->assertTrue($role->can('proposal:categorize'));
    }

    /** @test */
    public function it_can_remove_a_permission()
    {
        $role = Role::byName('customer')->first();
        $this->assertTrue($role->can('proposal:submit')); //We need to make sure the permission is there initially
        $role->removePermissionTo('proposal:submit');
        $this->assertFalse($role->can('proposal:submit'));
    }
}
