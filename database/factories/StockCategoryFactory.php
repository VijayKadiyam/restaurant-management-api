<?php

use Faker\Generator as Faker;

$factory->define(\App\StockCategory::class, function (Faker $faker) {
  return [
    'company_id'  =>  factory(\App\Company::class)->create()->id,
    'name'        =>  'Fly Ash',
    'unit_id'     =>  factory(\App\Unit::class)->create()->id
  ];
});
