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
                'project:attachment:download',
            ],
            'student' => [
                'project:view',
                'project:comment',
                'project:submit',
                'project:publish',
                'attachment:delete',
                'project:attachment:download',
            ],
            'supervisor' => [
                'project:view',
                'project:comment',
                'project:submit',
                'project:approve',
                'attachment:delete',
                'project:attachment:download',
            ],
            'teacher' => [
                'project:view',
                'project:comment',
                'project:submit',
                'project:edit',
                'project:approve',
                'attachment:delete',
                'project:attachment:download',
            ],
        ];

        $projectRoles->each(function ($projectRole) use ($permissions) {
            foreach ($permissions[$projectRole->name] as $perm) {
                $projectRole->givePermissionTo($perm);
            }
        });
    }
}
