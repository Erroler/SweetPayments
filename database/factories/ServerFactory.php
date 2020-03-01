<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Server;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Server::class, function (Faker $faker) {
    $max_slots = $faker->randomElement(['12', '16', '20', '24', '28', '32', '40']);
    $curr_players = $faker->numberBetween(0, $max_slots);
    return [
        'name' => $faker->name,
        'address' => $faker->ipv4.':'.$faker->numberBetween(20001, 29999),
        'map' => $faker->randomElement(['am_must2014_v2', 'awp_lego_csgo_v2', 'am_breakout_v2', 'am_desert_chn', 'de_inferno']),
        'players' => $curr_players .'/'.$max_slots
    ];
});
