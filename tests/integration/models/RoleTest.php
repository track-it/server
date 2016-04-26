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

        $canSubmit = $role->can('submit');
        $canView = $role->can('view');
        $canPublish = $role->can('publish');
        $canSearch = $role->can('search');
        $canApprove = $role->can('approve');

        $this->assertTrue($canSubmit);
        $this->assertTrue($canView);
        $this->assertFalse($canPublish);
        $this->assertFalse($canSearch);
        $this->assertFalse($canApprove);
    }

    /** @test */
    public function it_can_be_given_a_permission()
    {
        $role = Role::byName('customer')->first();
        $this->assertFalse($role->can('categorize')); //We need to make sure the permission is not there initially
        $role->givePermissionTo('categorize');
        $this->assertTrue($role->can('categorize'));
    }

    /** @test */
    public function it_can_remove_a_permission()
    {
        $role = Role::byName('customer')->first();
        $this->assertTrue($role->can('submit')); //We need to make sure the permission is there initially
        $role->removePermissionTo('submit');
        $this->assertFalse($role->can('submit'));
    }
}
