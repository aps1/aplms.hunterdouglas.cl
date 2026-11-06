<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Enums\UserTypes;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'cel' => $faker->phoneNumber,
        'cel_whatsapp' => $faker->phoneNumber,
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'dealership_id' => null,
        'remember_token' => Str::random(10),
        'is_active' => 1,
        'localization' => 'cl',
    ];
});

$factory->state(App\User::class, 'admin', [
    'type' => UserTypes::Admin,
    'dealership_id' => null,
]);

$factory->state(App\User::class, 'seller', [
    'type' => UserTypes::Seller,
]);

$factory->state(App\User::class, 'owner', [
    'type' => UserTypes::Owner,
]);

$factory->state(App\User::class, 'manager', [
    'type' => UserTypes::Manager,
]);

$factory->state(App\User::class, 'kam', [
    'type' => UserTypes::Kam,
]);

$factory->state(App\User::class, 'cl', [
    'localization' => 'cl',
]);

$factory->state(App\User::class, 'ar', [
    'localization' => 'ar',
]);
