<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Sprint;
use Faker\Generator as Faker;

$factory->define(Sprint::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'created_by' => 1,
    ];
});
