<?php

use Faker\Generator as Faker;

$factory->define(\App\ProductCategory::class, function (Faker $faker) {
  return [
    'menu_category_id'  =>  factory(\App\MenuCategory::class)->create()->id,
    'company_id'        =>  factory(\App\Company::class)->create()->id,
    'name'              =>  'Silico Plast',
    'price'             =>  '5000'

  ];
});
