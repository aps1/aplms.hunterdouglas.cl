<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Activity;
use App\Enums\ActivityTypes;
use Faker\Generator as Faker;

$factory->define(Activity::class, function (Faker $faker) {
    return [
        'type' => ActivityTypes::getRandomValue(),
        'user_id' => function () {
            return factory(\App\User::class)->create()->id;
        },
        'lead_id' => function () {
            return factory(\App\Lead::class)->create()->id;
        },
        'observation' => $faker->sentence,
        'reason' => null,
    ];
});
