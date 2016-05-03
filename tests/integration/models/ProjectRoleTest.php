<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Trackit\Models\ProjectRole;

class ProjectRoleTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function teacher_has_all_permissions()
    {
        $role = ProjectRole::byName('teacher')->first();
        // dd($role);
        $canView = $role->can('project:view');
        $canComment = $role->can('project:comment');
        $canSubmit = $role->can('project:submit');
        $canApprove = $role->can('project:approve');
        $canEdit = $role->can('project:edit');
        $canPublish = $role->can('project:publish');

        $this->assertTrue($canView);
        $this->assertTrue($canComment);
        $this->assertTrue($canSubmit);
        $this->assertTrue($canApprove);
        $this->assertTrue($canEdit);
        $this->assertTrue($canPublish);
    }

    /** @test */
    public function stakeholder_should_only_have_permission_to_view_and_comment()
    {
        $role = ProjectRole::byName('stakeholder')->first();

        $canView = $role->can('project:view');
        $canComment = $role->can('project:comment');
        $canSubmit = $role->can('project:submit');
        $canApprove = $role->can('project:approve');
        $canEdit = $role->can('project:edit');
        $canPublish = $role->can('project:publish');

        $this->assertTrue($canView);
        $this->assertTrue($canComment);
        $this->assertFalse($canSubmit);
        $this->assertFalse($canApprove);
        $this->assertFalse($canEdit);
        $this->assertFalse($canPublish);
    }

    /** @test */
    public function it_can_be_given_a_permission()
    {
        $role = ProjectRole::byName('student')->first();
        $this->assertFalse($role->can('project:edit')); //We need to make sure the permission is not there initially
        $role->givePermissionTo('project:edit');
        $this->assertTrue($role->can('project:edit'));
    }

    /** @test */
    public function it_can_have_a_permission_removed()
    {
        $role = ProjectRole::byName('supervisor')->first();
        $this->assertTrue($role->can('project:approve')); //We need to make sure the permission is there initially
        $role->removePermissionTo('project:approve');
        $this->assertFalse($role->can('project:approve'));
    }
}