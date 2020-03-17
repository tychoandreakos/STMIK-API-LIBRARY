<?php

use App\Gmd;
use Faker\Generator as Faker;

$factory->define(Gmd::class, function (Faker $faker) {
  return [
    'gmd_code' => $faker->randomNumber(3),
    'gmd_name' => $faker->name
  ];
});
