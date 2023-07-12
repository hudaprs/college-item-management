<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Po;
use Faker\Generator as Faker;

$factory->define(Po::class, function (Faker $faker) {
    return [
    	'po_nip' => rand(),
        'name' => $faker->name,
        'created_by'=> 1
    ];
});
