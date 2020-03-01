<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Sale;
use Faker\Generator as Faker;

$factory->define(Sale::class, function (Faker $faker) {
    $price = $faker->randomFloat(2, 1, 50);
    $steamids = file(__DIR__.'/steamids.txt', FILE_IGNORE_NEW_LINES);
    return [
        'player_name' => $faker->name,
        'steamid64' => $faker->randomElement($steamids),
        'ip_address' => $faker->ipv4,
        'payment_method' => $faker->randomElement(['paysafecard','paypal']),
        'revenue_before_tax' => $price,
        'revenue_after_tax' => $price*$faker->randomFloat(2, 0.5, 1)
    ];
});
