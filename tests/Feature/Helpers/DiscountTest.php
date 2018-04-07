<?php

namespace Tests\Feature\Helpers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DiscountTest extends TestCase
{
  use DatabaseTransactions;

  /** @test */
  function user_must_be_logged_in()
  {
    $this->json('post', '/api/discounts')
      ->assertStatus(401);
  }

  /** @test */
  function it_requires_name_and_discount_percent()
  {
    $this->json('post', '/api/discounts', [], $this->headers)
      ->assertStatus(422);
  } 

  /** @test */
  function discounts_fetched_successfully()
  {
    factory(\App\Discount::class)->create([
      'company_id'        =>  $this->company->id,
      'name'              =>  "18 percent",
      'discount_percent'  =>  "18"
    ]);

    $this->json('get', '/api/discounts', [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'name'  =>  "18 percent",
            'discount_percent'  =>  "18"
          ]
        ]
      ]); 
  }

  /** @test */
  function discount_saved_successfully()
  {
    $payload = [
      'name'  =>  "18 percent",
      'discount_percent'  =>  "18"
    ];

    $this->json('post', '/api/discounts', $payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'name'  =>  "18 percent",
          'discount_percent'  =>  "18"
        ]
      ]); 
  }

  /** @test */
  function single_discount_fetched_successfully()
  {
    $discount = factory(\App\Discount::class)->create([
      'company_id'        =>  $this->company->id,
      'name'              =>  "18 percent",
      'discount_percent'  =>  "18"
    ]);

    $this->json('get', "/api/discounts/$discount->id", [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'name'              =>  "18 percent",
          'discount_percent'  =>  "18"
        ]
      ]); 
  }

  /** @test */
  function it_requires_name_and_discount_percent_while_updating()
  {
    $discount = factory(\App\Discount::class)->create([
      'company_id'        =>  $this->company->id,
      'name'              =>  "18 percent",
      'discount_percent'  =>  "18"
    ]);

    $this->json('patch', "/api/discounts/$discount->id", [], $this->headers)
      ->assertStatus(422);
  }

  /** @test */
  function discount_updated_successfully()
  {
    $discount = factory(\App\Discount::class)->create([
      'company_id'        =>  $this->company->id,
      'name'              =>  "18 percent",
      'discount_percent'  =>  "18"
    ]);
    $discount->name = "20 percent";
    $discount->discount_percent = "20";

    $this->json('patch', "/api/discounts/$discount->id", $discount->toArray(), $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'name'  =>  "20 percent",
          'discount_percent'  =>  '20'
        ]
      ]);
  }
}
