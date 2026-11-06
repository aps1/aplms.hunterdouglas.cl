<?php

use App\Enums\UserTypes;
use App\User;
use Illuminate\Database\Seeder;

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
            'name' => 'Javier Leon',
            'email' => 'javier.leon@oqo.cl',
            'password' => bcrypt('oqotribu2017'),
            'type' => UserTypes::Admin,
        ]);
    }
}
