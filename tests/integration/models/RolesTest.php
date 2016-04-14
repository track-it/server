<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Trackit\Models\Role;

class RolesTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    public function it_has_a_set_of_permissions()
    {
        // Given
        $role = Role::byName('customer')->first();

        // When
        $canSubmit = $role->can('submit');
        // $canView = $role->canView();
        // $canPublish = $role->canPublish();
        // $canSearch = $role->canSearch();
        // $canApprove = $role->canApprove();

        // Then
        $this->assertTrue($canSubmit);
        // $this->assertTrue($canView);
        // $this->assertFalse($canPublish);
        // $this->assertFalse($canSearch);
        // $this->assertFalse($canApprove);
    }
}
