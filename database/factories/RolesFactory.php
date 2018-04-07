<?php

use Faker\Generator as Faker;

$factory->define(\App\Role::class, function (Faker $faker) {
  return [
    'company_id'  =>  factory(\App\Company::class)->create()->id,
    'role'        =>  'User'
  ];
});
