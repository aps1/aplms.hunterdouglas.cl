<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\WebClient;
use Faker\Generator as Faker;

$factory->define(WebClient::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'token' => $faker->password,
        'is_active' => 1,
    ];
});
