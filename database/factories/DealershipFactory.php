<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Dealership;
use Faker\Generator as Faker;

$factory->define(Dealership::class, function (Faker $faker) {
    return [
        'identifier' => $faker->numberBetween(1555, 90000),
        'name' => $faker->word,
        'city' => $faker->city,
        'region' => $faker->word,
        'commune' => $faker->word,
        'address' => $faker->streetAddress,
        'phone' => $faker->phoneNumber,
        'email' => $faker->email,
        'wcp1' => $faker->boolean,
        'wcp2' => $faker->boolean,
        'is_active' => 1,
    ];
});
