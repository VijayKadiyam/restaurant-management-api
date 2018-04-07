<?php

namespace Tests\Feature\Order;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderTest extends TestCase
{ 
  use DatabaseTransactions;

  protected $menuCategory, $productCategory;

  public function setUp()
  {
    parent::setUp();

    $this->menuCategory = factory(\App\MenuCategory::class)->create([
      'company_id'  =>  $this->company->id,
      'name'        =>  'Desert'
    ]); 

    $this->productCategory = factory(\App\ProductCategory::class)->create([
      'menu_category_id'  =>  $this->menuCategory->id,
      'company_id'        =>  $this->company->id,
      'name'              =>  'Silicoplast',
      'price'             =>  '5000'
    ]);
  }

  /** @test */
  function user_must_be_logged_on()
  {
    $this->json('post', '/api/orders')
      ->assertStatus(401); 
  }

  /** @test */
  function order_saved_successfully()
  {
    $this->json('post', '/api/orders', [], $this->headers)
      ->assertStatus(201)
      ->assertJsonStructure([
        'data'  =>  [
          'id', 'company_id'
        ]
      ]);
  } 

}
