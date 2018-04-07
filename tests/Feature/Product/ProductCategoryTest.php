<?php

namespace Tests\Feature\Product;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductCategoryTest extends TestCase
{
  use DatabaseTransactions;
  
  /** @test */
  function user_must_be_logged_in()
  {
    $this->json('post', '/api/product-categories')
      ->assertStatus(401); 
  }

  /** @test */
  function it_requires_name_menuCategoryId_price()
  {
     $this->json('post', '/api/product-categories', [], $this->headers)
      ->assertStatus(422);
  }

  /** @test */
  function product_categories_fetched_successfully()
  {
  
    $menuCategory = factory(\App\MenuCategory::class)->create([
      'company_id'  =>  $this->company->id,
      'name'        =>  'Desert'
    ]); 

    factory(\App\ProductCategory::class)->create([
      'menu_category_id'  =>  $menuCategory->id,
      'company_id'        =>  $this->company->id,
      'name'              =>  'Silicoplast',
      'price'             =>  '5000'
    ]);

    $this->json('get', '/api/product-categories', [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'menu_category_id'  =>  $menuCategory->id,
            'name'              =>  'Silicoplast',
            'price'             =>  '5000'
          ]
        ]
      ]);
  }

  /** @test */
  function product_category_saved_successfully()
  {
    $this->disableEH();

    $menuCategory = factory(\App\MenuCategory::class)->create([
      'company_id'  =>  $this->company->id,
      'name'        =>  'Desert'
    ]); 

    $payload = [
      'menu_category_id'  =>  $menuCategory->id,
      'name'              =>  'Silicoplast',
      'price'             =>  '5000',
    ];

    $this->json('post', '/api/product-categories', $payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'company_id'  =>  $this->company->id,
          'name'        =>  'Silicoplast',
          'price'       =>  '5000'
        ]
      ]); 
  }

  /** @test */
  function single_product_category_fetched_successfully()
  {
    $menuCategory = factory(\App\MenuCategory::class)->create([
      'company_id'  =>  $this->company->id,
      'name'        =>  'Desert'
    ]); 

    $productCategory = factory(\App\ProductCategory::class)->create([
      'menu_category_id'  =>  $menuCategory->id,
      'company_id'        =>  $this->company->id,
      'name'              =>  'Silicoplast',
      'price'             =>  '5000'
    ]);

    $this->json('get', "/api/product-categories/$productCategory->id", [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'menu_category_id'  =>  $menuCategory->id,
          'name'              =>  'Silicoplast',
          'price'             =>  '5000'
        ]
      ]);
  }

  /** @test */
  function it_requires_name_while_updating()
  {
    $menuCategory = factory(\App\MenuCategory::class)->create([
      'company_id'  =>  $this->company->id,
      'name'        =>  'Desert'
    ]); 

    $productCategory = factory(\App\ProductCategory::class)->create([
      'menu_category_id'  =>  $menuCategory->id,
      'company_id'        =>  $this->company->id,
      'name'              =>  'Silicoplast',
      'price'             =>  '5000'
    ]);
    $productCategory->name = "Silica";

    $this->json('patch', "/api/product-categories/$productCategory->id", [], $this->headers)
      ->assertStatus(422);
  }

  /** @test */
  function product_updated_successfully()
  {
    $menuCategory = factory(\App\MenuCategory::class)->create([
      'company_id'  =>  $this->company->id,
      'name'        =>  'Desert'
    ]); 

    $productCategory = factory(\App\ProductCategory::class)->create([
      'menu_category_id'  =>  $menuCategory->id,
      'company_id'        =>  $this->company->id,
      'name'              =>  'Silicoplast',
      'price'             =>  '5000'

    ]);
    $productCategory->name = "Silica";

    $this->json('patch', "/api/product-categories/$productCategory->id", $productCategory->toArray(), $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'menu_category_id'  =>  $menuCategory->id,
          'name'              =>  'Silica',
          'price'             =>  '5000'
        ]
      ]);
  }

  /** @test */
  function a_stock_category_can_be_added_to_the_product_category()
  {
    $menuCategory = factory(\App\MenuCategory::class)->create([
      'company_id'  =>  $this->company->id,
      'name'        =>  'Desert'
    ]); 

    $productCategory = factory(\App\ProductCategory::class)->create([
      'menu_category_id'  =>  $menuCategory->id,
      'company_id'        =>  $this->company->id,
      'name'              =>  'Silicoplast',
      'price'             =>  '5000'
    ]);

    $stockCategory1 = factory(\App\StockCategory::class)->create([
      'name'        =>  'Fly Ash',
      'company_id'  =>  $this->company->id
    ]);

    $stockCategory2 = factory(\App\StockCategory::class)->create([
      'name'        =>  'Silica',
      'company_id'  =>  $this->company->id
    ]);

    $value1 = "20";
    $value2 = "20";

    $productCategory->addStockCategory($stockCategory1, $value1);
    $productCategory->addStockCategory($stockCategory2, $value2);

    $this->assertCount(2, $productCategory->stock_categories);

    $this->assertEquals('20' , $productCategory->stock_categories[0]->pivot->value);
  }

  /** @test */
  function product_added_successfully_with_stock_category()
  { 
    $this->disableEH();
    $menuCategory = factory(\App\MenuCategory::class)->create([
      'company_id'  =>  $this->company->id,
      'name'        =>  'Desert'
    ]); 

    $stockCategory1 = factory(\App\StockCategory::class)->create([
      'name'        =>  'Fly Ash',
      'company_id'  =>  $this->company->id
    ]); 

    $payload = [
      'menu_category_id'  =>  $menuCategory->id,
      'name'  =>  'Silicoplast',
      'price'             =>  '5000',
      'stock_categories'  =>  [
        0 =>   [
        'id'    =>  $stockCategory1->id,
        'value' =>  '20'
        ]
      ]
    ]; 

    $this->json('post', '/api/product-categories', $payload, $this->headers)
      ->assertStatus(201);

    $productCategory = \App\ProductCategory::find(1);
    $this->assertCount(1, $productCategory->stock_categories);
    $this->assertEquals('20' , $productCategory->stock_categories[0]->pivot->value);
  }
}
