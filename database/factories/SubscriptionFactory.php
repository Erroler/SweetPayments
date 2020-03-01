<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Subscription;
use Faker\Generator as Faker;

$factory->define(Subscription::class, function (Faker $faker) {
    
    return [
        'name' => $faker->name,
        'immunity' => $faker->numberBetween(0, 100),
        'duration' => $faker->numberBetween(1, 365),
        'pricing' => $faker->randomFloat(2, 1, 5),
        'payment_methods' => $faker->randomElements(["paysafecard","paypal"], $faker->numberBetween(1, 2)),
        'flags' => $faker->randomElements(['a', 'b', 'c', 'd', 'e', 'f', 'm', 'o', 'z'], $faker->numberBetween(1, 6)),
    ];
});
