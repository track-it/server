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
