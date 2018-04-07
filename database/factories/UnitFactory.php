<?php

use Faker\Generator as Faker;

$factory->define(\App\Unit::class, function (Faker $faker) {
  return [
    'company_id'  =>  '1',
    'unit'  =>  'Kg'
  ];  
});
