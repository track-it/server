<?php

use Illuminate\Database\Seeder;
use Trackit\Models\Role;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = Role::all();

        $permissions = [
            'customer' => [
                'proposal:submit',
                'proposal:view',
                'proposal:list',
                'project:view',
                'project:list',
            ],
            'student' => [
                'proposal:apply',
                'proposal:submit',
                'proposal:view',
                'project:view',
                'project:list',
            ],
            'teacher' => [
                'proposal:submit',
                'proposal:view',
                'proposal:approve',
                'proposal:categorize',
                'proposal:comment',
                'proposal:edit:status',
                'project:create',
                'project:view',
                'project:list',
            ],
            'administrator' => [
                'proposal:submit',
                'proposal:view',
                'proposal:edit',
                'proposal:delete',
                'proposal:approve',
                'proposal:categorize',
                'proposal:comment',
                'comment:view',
                'comment:edit',
                'comment:delete',
                'tag:view',
                'tag:edit',
                'tag:delete',
                'project:view',
                'project:comment',
                'project:edit',
                'project:delete',
                'project:publish',
                'team:view',
                'team:edit',
                'team:delete',
            ],
            'guest' => [
                'proposal:view',
                'proposal:list',
                'project:view',
                'project:list',
            ],
        ];

        $roles->each(function ($role) use ($permissions) {
            foreach ($permissions[$role->name] as $perm) {
                $role->givePermissionTo($perm);
            }
        });
    }
}
