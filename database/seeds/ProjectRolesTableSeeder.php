<?php

use Illuminate\Database\Seeder;
use Trackit\Models\ProjectRole;

class ProjectRolesTableSeeder extends Seeder
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
                'name'      => 'stakeholder'
            ],
            [
                'name'      => 'student'
            ],
            [
                'name'      => 'teacher'
            ],
            [
                'name'      => 'supervisor'
            ],
        ];

        foreach ($roles as $role) {
            ProjectRole::firstOrCreate($role);
        }
    }
}
