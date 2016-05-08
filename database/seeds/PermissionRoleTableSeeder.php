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
            ],
            'student' => [
                'proposal:submit',
                'proposal:view',
                'proposal:publish',
            ],
            'teacher' => [
                'proposal:submit',
                'proposal:view',
                'proposal:approve',
                'proposal:categorize',
            ],
            'administrator' => [
                'proposal:submit',
                'proposal:view',
                'proposal:edit',
                'proposal:delete',
                'proposal:publish',
                'proposal:approve',
                'proposal:categorize',
                'comment:view',
                'comment:edit',
                'comment:delete',
                'tag:view',
                'tag:edit',
                'tag:delete',
                'project:view',
                'project:edit',
                'project:delete',
            ],
        ];

        $roles->each(function ($role) use ($permissions) {
            foreach ($permissions[$role->name] as $perm) {
                $role->givePermissionTo($perm);
            }
        });
    }
}
