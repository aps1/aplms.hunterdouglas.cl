<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Folio;
use App\Lead;
use Faker\Generator as Faker;

$factory->define(Folio::class, function (Faker $faker) {
    return [
        'number' => $faker->numberBetween(1000, 2000),
        'lead_id' => factory(Lead::class)->create()->id,
        'localization' => 'cl',
    ];
});
