<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Purchase;
use App\Activity;
use Faker\Generator as Faker;

$factory->define(Purchase::class, function (Faker $faker) {
    $activity = rand(1,500);
    $guest = rand(1,10);
    $payment_method = ['Credit Card','Bank Transfer','GoPay'];
    $status = ['Pending','Expired','Success','Finished'];
    return [
        'activity_id' => $activity,
        'buyer_id' => rand(1,1000),
        'guest' => $guest,
        'gross_total' => Activity::find($activity)->price * $guest,
        'payment_method' => $payment_method[rand(0,2)],
        'status' => $status[rand(0,3)]
    ];
});
