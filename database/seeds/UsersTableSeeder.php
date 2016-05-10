<?php

use Illuminate\Database\Seeder;

use Trackit\Models\User;
use Trackit\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'guest',
            'password' => bcrypt(str_random(100)),
            'role_id' => Role::byName('guest')->first()->id,
        ]);
    }
}
