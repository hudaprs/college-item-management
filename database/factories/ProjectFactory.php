<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\project;
use Faker\Generator as Faker;

$factory->define(project::class, function (Faker $faker) {
    return [
        'po_id' => 1,
        'project_code' => 'PRO' . '-' . rand(),
        'name' => $faker->name,
        'desc' => $faker->sentence,
        'created_by' => 1,
    ];
});