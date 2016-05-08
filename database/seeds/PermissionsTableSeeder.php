<?php

use Illuminate\Database\Seeder;
use Trackit\Models\Permission;
use Trackit\Models\Role;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        $customer = Role::where(['name' => 'customer'])->first();
        $student = Role::where(['name' => 'student'])->first();
        $teacher = Role::where(['name' => 'teacher'])->first();
        $administrator = Role::where(['name' => 'administrator'])->first();

        $roles = Role::all();

        $permissions = [
            'customer' => [
                'proposal:submit',
                'proposal:view',
                'project:view',
            ],
            'student' => [
                'proposal:submit',
                'proposal:view',
                'proposal:apply',
                'project:view',
            ],
            'teacher' => [
                'proposal:submit',
                'proposal:view',
                'proposal:approve',
                'proposal:categorize',
                'project:view',
            ],
            'administrator' => [
                'proposal:submit',
                'proposal:view',
                'proposal:approve',
                'proposal:delete',
                'proposal:edit',
                'proposal:categorize',
                'project:view',
                'project:edit',
                'project:delete',
                'comment:view',
                'comment:edit',
                'comment:delete',
                'tag:view',
                'tag:edit',
                'tag:delete',
        */

        $permissions = [
            [
                'name'      => 'proposal:view'
            ],
            [
                'name'      => 'proposal:submit'
            ],
            [
                'name'      => 'proposal:search'
            ],
            [
                'name'      => 'proposal:approve'
            ],
            [
                'name'      => 'proposal:publish'
            ],
            [
                'name'      => 'proposal:categorize'
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate($permission);
        }
    }
}
