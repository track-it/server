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
                'proposal:search',
            ],
            'teacher' => [
                'proposal:submit',
                'proposal:view',
                'proposal:approve',
                'proposal:categorize',
                'proposal:search',
            ],
            'administrator' => [
                'proposal:submit',
                'proposal:view',
                'proposal:publish',
                'proposal:search',
                'proposal:approve',
                'proposal:categorize',
            ],
        ];

        $roles->each(function ($role) use ($permissions) {
            foreach ($permissions[$role->name] as $perm) {
                $role->givePermissionTo($perm);
            }
        });
    }
}
