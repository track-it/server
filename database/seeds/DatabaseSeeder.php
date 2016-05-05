<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(PermissionRoleTableSeeder::class);
        $this->call(ProjectRolesTableSeeder::class);
        $this->call(ProjectPermissionsTableSeeder::class);
        $this->call(ProjectPermissionRoleTableSeeder::class);
    }
}
