<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Dealership;
use App\Enums\LeadOrigins;
use App\Enums\LeadPriority;
use App\Enums\LeadStatus;
use App\Lead;
use Faker\Generator as Faker;

$factory->define(Lead::class, function (Faker $faker) {
    if (Dealership::count()) {
        $dealership = Dealership::inRandomOrder()->first();
    } else {
        $dealership = factory(\App\Dealership::class)->create();
    }

    return [
        'correlative' => $faker->numberBetween(1, 1000),
        'web_identifier' => $faker->numberBetween(1000, 5000),
        'name' => $faker->name(),
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,
        'country' => $faker->country,
        'geo_loc1' => $faker->city,
        'geo_loc2' => $faker->city,
        'geo_loc3' => $faker->city,
        'address' => $faker->streetAddress,
        'brand' => $faker->randomElement(['HunterDouglas', 'Flexalum']),
        'origin' => LeadOrigins::getRandomValue(),
        'date' => $faker->dateTimeThisMonth(),
        'is_subscribed' => $faker->boolean(),
        'product' => $faker->word,
        'dealersite' => $dealership->name,
        'dealership_id' => $dealership->id,
        'page_title' => $faker->word,
        'status' => LeadStatus::getRandomValue(),
        'status_reason' => null,
        'priority' => LeadPriority::getRandomValue(),
        'message' => $faker->text(),
        'user_id' => null,
        'contact_id' => null,
        'localization' => 'cl',
    ];
});

$factory->state(App\Lead::class, 'lost', function ($faker) {
    return [
        'status' => LeadStatus::Perdido,
    ];
});

$factory->state(App\Lead::class, 'won', function ($faker) {
    return [
        'status' => LeadStatus::Vendido,
    ];
});

$factory->state(App\Lead::class, 'open', function ($faker) {
    return [
        'user_id' => null,
        'status' => LeadStatus::Creado, // tambien puede ser derivada
    ];
});
