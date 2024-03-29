<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'price' => $faker->randomFloat(2, 1, 100),
        'ordinal' => $faker->randomDigitNotNull,
    ];
});

// $factory->state(App\Product::class, 'starter', [
//    'id' => App\Product::STARTER,
// ]);

// $factory->state(App\Product::class, 'master', [
//     'id' => App\Product::MASTER,
// ]);
