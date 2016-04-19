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
    	$customer = Role::where(['name' => 'customer'])->first();
    	$student = Role::where(['name' => 'student'])->first();
    	$teacher = Role::where(['name' => 'teacher'])->first();
    	$administrator = Role::where(['name' => 'administrator'])->first();

        $roles = Role::all();

        $permissions = [
            'customer' => [
                'submit',
                'view',
            ],
            'student' => [
                'submit',
                'view',
                'publish',
                'search',
            ],
            'teacher' => [
                'submit',
                'view',
                'approve',
                'categorize',
                'search',
            ],
            'administrator' => [
                'submit',
                'view',
                'publish',
                'search',
                'approve',
                'categorize',
                'search',
            ],
        ];


        $roles->each(function ($role) use ($permissions) {
            foreach ($permissions[$role->name] as $perm) {
                $role->givePermissionTo($perm);
            }
        });
    }
}
