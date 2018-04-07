<?php

use Faker\Generator as Faker;

$factory->define(\App\Order::class, function (Faker $faker) {
  return [
    'company_id'  =>  factory(\App\Company::class)->create()->id
  ];
});
