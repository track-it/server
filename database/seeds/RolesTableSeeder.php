<?php

use Illuminate\Database\Seeder;
use Trackit\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name'      => 'customer'
            ],
            [
                'name'      => 'student'
            ],
            [
                'name'      => 'teacher'
            ],
            [
                'name'      => 'administrator'
            ],
            [
                'name'      => 'guest'
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate($role);
        }
    }
}
