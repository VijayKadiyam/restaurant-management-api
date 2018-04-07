<?php

namespace Tests\Feature\Stock;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StockTest extends TestCase
{
  use DatabaseTransactions;

  protected $supplier, $stockCategory;

  public function setUp()
  {
    parent::setUp();

    $this->supplier = factory(\App\Supplier::class)->create([
      'company_id'  => $this->company->id
    ]);

    $this->stockCategory = factory(\App\StockCategory::class)->create([
      'company_id'  =>  $this->company->id
    ]);
  }

  /** @test */
  function user_must_be_logged_in()
  {
    $this->json('post', '/api/stocks')
      ->assertStatus(401); 
  }

  /** @test */
  function it_requires_supplierId_stockCategoryId_price_and_qty()
  {
    $this->json('post', '/api/stocks', [], $this->headers)
      ->assertStatus(422); 
  }

  /** @test */
  function stocks_fetched_successfully()
  {
    factory(\App\Stock::class)->create([
      'company_id'  =>  $this->company->id,
      'supplier_id' => $this->supplier->id,
      'stock_category_id' => $this->stockCategory->id,
      'price'  => 200,
      'qty'    => 10
    ]);

    $this->json('get', '/api/stocks', [], $this->headers)
      ->assertStatus(200)
      ->assertJsonStructure([
        'data'  =>  [
          0 =>  [
            'supplier_id',
            'stock_category_id',
            'price',
            'qty'
          ]
        ]
      ]); 
  }

  /** @test */
  function stock_saved_successfully()
  {
    $payload = [
      'supplier_id' => $this->supplier->id,
      'stock_category_id' => $this->stockCategory->id,
      'price'  => 200,
      'qty'    => 10
    ];

    $this->json('post', '/api/stocks', $payload, $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'supplier_id' => $this->supplier->id,
          'stock_category_id' => $this->stockCategory->id,
          'price'  => 200,
          'qty'    => 10
        ]
      ]);
  }

  /** @test */
  function single_stock_fetched_successfully()
  {
    $stock = factory(\App\Stock::class)->create([
      'company_id'  =>  $this->company->id,
      'supplier_id' => $this->supplier->id,
      'stock_category_id' => $this->stockCategory->id,
      'price'  => 200,
      'qty'    => 10
    ]);

    $this->json('get', "/api/stocks/$stock->id", [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'company_id'  =>  $this->company->id,
          'supplier_id' => $this->supplier->id,
          'stock_category_id' => $this->stockCategory->id,
          'price'  => 200,
          'qty'    => 10
        ]
      ]);
  }

  /** @test */
  function stock_updated_successfully()
  {
    $stock = factory(\App\Stock::class)->create([
      'company_id'  =>  $this->company->id,
      'supplier_id' => $this->supplier->id,
      'stock_category_id' => $this->stockCategory->id,
      'price'  => 200,
      'qty'    => 10
    ]);
    $stock->price = 2000;

    $this->json('patch', "/api/stocks/$stock->id", $stock->toArray(), $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'company_id'  =>  $this->company->id,
          'supplier_id' => $this->supplier->id,
          'stock_category_id' => $this->stockCategory->id,
          'price'  => 2000,
          'qty'    => 10
        ]
      ]);
  }
}
