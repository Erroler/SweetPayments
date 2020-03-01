<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\User;
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

$factory->define(User::class, function (Faker $faker) {
    
    $steamids = file(__DIR__.'/steamids.txt', FILE_IGNORE_NEW_LINES);
    return [
        'username' => $faker->name,
        'steamid' => $faker->randomElement($steamids),
        'avatar' => $faker->imageUrl(200, 200),
        'profile_url' => $faker->url,
    ];
});
