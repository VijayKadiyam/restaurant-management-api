<?php

use Faker\Generator as Faker;

$factory->define(\App\Tax::class, function (Faker $faker) {
  return [
    'company_id'  =>  factory(\App\Company::class)->create()->id,
    'name'        =>  'GST',
    'tax_percent' =>  '18'
  ];
});
