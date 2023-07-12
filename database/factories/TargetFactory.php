<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Target;
use Faker\Generator as Faker;

$factory->define(Target::class, function (Faker $faker) {
    return [
        'project_id' => 1,
        'sprint_id' => 1,
        'name' => $faker->name,
        'status' => 'DONE',
        'created_by' => 1
    ];
});