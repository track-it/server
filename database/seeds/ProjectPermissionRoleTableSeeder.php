<?php

use Illuminate\Database\Seeder;
use Trackit\Models\ProjectRole;
use Trackit\Models\ProjectPermission;

class ProjectPermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $projectRoles = ProjectRole::all();
                       
        $permissions = [
            'stakeholder' => [
                'project:view',
                'project:comment',
            ],
            'student' => [
                'project:view',
                'project:comment',
                'project:submit',
                'project:publish',
            ],
            'supervisor' => [
                'project:view',
                'project:comment',
                'project:submit',
                'project:approve',
            ],
            'teacher' => [
                'project:view',
                'project:comment',
                'project:submit',
                'project:publish',
                'project:edit',
                'project:approve',
            ],
        ];

        $projectRoles->each(function ($projectRole) use ($permissions) {
            foreach ($permissions[$projectRole->name] as $perm) {
            	$projectRole->givePermissionTo($perm);
            }
        });
    }
}