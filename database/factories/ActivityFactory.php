<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Activity;
use Faker\Generator as Faker;

$factory->define(Activity::class, function (Faker $faker) {
    return [
        'title' => $faker->text,
        'price' => rand(50000,500000),
        'description' => $faker->text,
        'host_id' => rand(1,100),
        'category_id' => rand(1,12),
        'location_id' => rand(1,16),
    ];
});
