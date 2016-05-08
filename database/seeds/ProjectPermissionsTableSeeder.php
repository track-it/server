<?php

use Illuminate\Database\Seeder;
use Trackit\Models\ProjectPermission;

class ProjectPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'name'      => 'project:view'
            ],
            [
                'name'      => 'project:comment'
            ],
            [
                'name'      => 'project:submit'
            ],
            [
                'name'      => 'project:publish'
            ],
            [
                'name'      => 'project:edit'
            ],
            [
                'name'      => 'project:approve'
            ],
        ];

        foreach ($permissions as $permission) {
            ProjectPermission::firstOrCreate($permission);
        }
    }
}
