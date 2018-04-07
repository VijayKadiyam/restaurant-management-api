<?php

use Faker\Generator as Faker;

$factory->define(\App\Discount::class, function (Faker $faker) {
  return [
    'company_id'  =>  factory(\App\Company::class)->create()->id,
    'name'        =>  '5% discount',
    'discount_percent' =>  '5'
  ];
});
