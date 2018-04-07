<?php

use Faker\Generator as Faker;

$factory->define(\App\Stock::class, function (Faker $faker) {
  return [
    'company_id'  =>  factory(\App\Company::class)->create()->id,
    'supplier_id' =>  factory(\App\Supplier::class)->create()->id,
    'stock_category_id' =>  factory(\App\StockCategory::class)->create()->id,
    'price'  => 200,
    'qty'    => 10
  ];
});
