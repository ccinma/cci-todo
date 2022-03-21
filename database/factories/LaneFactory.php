<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Lane;
use Faker\Generator as Faker;

$factory->define(Lane::class, function (Faker $faker) {
    return [
        'name' => $faker->text(25)
    ];
});
