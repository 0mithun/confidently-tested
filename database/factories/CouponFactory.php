<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Coupon;
use Faker\Generator as Faker;

$factory->define(Coupon::class, function (Faker $faker) {
    return [
        'code'          =>  $faker->md5,
        'percent_off'   =>  $faker->numberBetween(1, 100),
        'expired_at'    =>  null,

    ];
});
