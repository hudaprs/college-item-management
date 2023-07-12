<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
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
    return [
    	'image' => "noimage.png",
    	'nip' => rand(),
        'name' => $faker->name,
        'divisi' => 'BACKEND',
        'phone' => rand(),
        'email' => $faker->unique()->safeEmail,
        'email_secondary' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => \Hash::make(12345678),
        'level' => 'C-LEVEL',
        'status' => 'ACTIVE',
        'created_by' => 1,
        'remember_token' => Str::random(10),
    ];
});
