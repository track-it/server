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
            'proposal:view',
            'proposal:edit',
            'proposal:delete',
            'proposal:submit',
            'proposal:approve',
            'proposal:categorize',
            'proposal:apply',
            'proposal:publish',
            'project:view',
            'project:edit',
            'project:delete',
            'comment:view',
            'comment:edit',
            'comment:delete',
            'tag:view',
            'tag:edit',
            'tag:delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
            ]);
        }
    }
}
