<?php

use App\Enums\UserTypes;
use App\User;
use Illuminate\Database\Seeder;

class PersonalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crea Sellers
        factory(User::class, 30)->state('seller')->create()
            ->each(function ($user) {
                $user->update(['dealership_id' => rand(1, 20)]);
            });

        // Crea owners
        factory(User::class, 20)->state('owner')->create()
            ->each(function ($user) {
                $user->update(['dealership_id' => rand(1, 20)]);
            });

        User::create([
            'name' => 'User Seller',
            'email' => 'seller@example.com',
            'password' => bcrypt('123456'),
            'type' => UserTypes::Seller,
            'dealership_id' => 1,
        ]);
        User::create([
            'name' => 'User Owner',
            'email' => 'owner@example.com',
            'password' => bcrypt('123456'),
            'type' => UserTypes::Owner,
            'dealership_id' => 1,
        ]);
    }
}
