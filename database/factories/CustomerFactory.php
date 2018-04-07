<?php

use Faker\Generator as Faker;

$factory->define(\App\Customer::class, function (Faker $faker) {
  return [
    'company_id'    =>  factory(\App\Company::class)->create()->id,
    'name'          =>  $faker->name,
    'email'         =>  $faker->email,
    'contact1'      =>  '08766555',
    'gstn_no'       =>  'GSTNNO',
    'state_code'    =>  '27',
    'address'       =>  $faker->address
  ];
});
