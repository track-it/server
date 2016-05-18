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
            'proposal:list',
            'proposal:view',
            'proposal:edit',
            'proposal:edit:status',
            'proposal:delete',
            'proposal:submit',
            'proposal:approve',
            'proposal:categorize',
            'proposal:apply',
            'proposal:publish',
            'proposal:comment',
            'project:create',
            'project:view',
            'project:list',
            'project:edit',
            'project:delete',
            'project:comment',
            'comment:view',
            'comment:edit',
            'comment:delete',
            'tag:view',
            'tag:edit',
            'tag:delete',
            'team:view',
            'team:edit',
            'team:delete',
            'attachment:delete',
            'attachment:view',
            'attachment:edit',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
            ]);
        }
    }
}
