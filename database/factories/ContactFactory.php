<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Contact;
use App\Dealership;
use Faker\Generator as Faker;

$factory->define(Contact::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'phone' => $faker->optional()->phoneNumber,
        'phone2' => $faker->optional()->phoneNumber,
        'address' => $faker->optional()->address,
        'geo_loc1' => $faker->optional()->city,
        'geo_loc2' => $faker->optional()->city,
        'geo_loc3' => $faker->optional()->city,
        'type' => null,
        'source' => null,
        'description' => null,
        'is_subscribed' => 1,
        'dealership_id' => factory(Dealership::class)->create()->id,
    ];
});
