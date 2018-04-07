<?php

namespace Tests\Feature\Stock;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StockCategoryTest extends TestCase
{
  use DatabaseTransactions; 

  protected $unit;

  public function setUp()
  {
    parent::SetUp();

    $this->unit = factory(\App\Unit::class)->create([
      'company_id'  =>  $this->company->id
    ]);
  }

  /** @test */
  function user_must_be_logged_in_to_access_the_controller()
  {
    $this->json('post', '/api/stock-categories')
      ->assertStatus(401); 
  }

  /** @test */
  function it_requires_name_and_unit_id()
  {
    $this->json('post', '/api/stock-categories', [], $this->headers)
      ->assertStatus(422);
  }

  /** @test */
  function stock_categories_are_fetched_successfully()
  {
    factory(\App\StockCategory::class)->create([
      'company_id'  =>$this->company->id
    ]);

    factory(\App\StockCategory::class)->create([
      'company_id'  =>$this->company->id
    ]);

    $this->json('get', '/api/stock-categories', [], $this->headers)
      ->assertStatus(200)
      ->assertJsonStructure([
        'data'  =>  [
          0 =>  [
            'name',
            'unit_id'
          ]
        ]
      ]);

    $this->assertCount(2, $this->company->stock_categories);
  }

  /** @test */
  function stock_category_is_added_successfully()
  {
    $payload = [
      'name'  => 'Fly Ash',
      'unit_id' =>  $this->unit->id
    ];

    $this->json('post', '/api/stock-categories', $payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'name'  =>  'Fly Ash',
          'unit_id' =>  $this->unit->id
        ]
      ]);
  }

  /** @test */
  function single_stock_category_is_fetched_successfully()
  {
    $category = factory(\App\StockCategory::class)->create([
      'name'        =>  'Fly Ash',
      'company_id'  =>  $this->company->id
    ]);

    $this->json('get', "/api/stock-categories/$category->id", [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'name'  =>  'Fly Ash',
          'company_id'  =>  $this->company->id
        ]
      ]);
  }

  /** @test */
  function it_requires_name_and_unit_id_while_updating()
  {
    $category = factory(\App\StockCategory::class)->create([
      'name'        =>  'Fly Ash',
      'company_id'  =>  $this->company->id
    ]);
    $category->name = "Cement";
    
    $this->json('patch', "/api/stock-categories/$category->id", [], $this->headers)
      ->assertStatus(422);
  }

  /** @test */
  function stock_category_updated_successfully()
  {
    $category = factory(\App\StockCategory::class)->create([
      'name'        =>  'Fly Ash',
      'company_id'  =>  $this->company->id
    ]);
    $category->name = "Cement";

    $this->json('patch', "/api/stock-categories/$category->id", $category->toArray(), $this->headers)
      ->assertStatus(200)
      ->assertJson([
          'data'  =>  [
            'name'        =>  'Cement',
            'company_id'  =>  $this->company->id
          ] 
      ]);
  }
}
