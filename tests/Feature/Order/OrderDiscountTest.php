<?php

namespace Tests\Feature\Order;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderDiscountTest extends TestCase
{
  use DatabaseTransactions;

  protected $discount, $order;

  public function setUp()
  {
    parent::setUp();

    $this->discount =  factory(\App\Discount::class)->create([
      'company_id'  =>  $this->company->id
    ]);

    $this->order = factory(\App\Order::class)->create([
      'company_id'  =>  $this->company->id
    ]);

   
  }

  /** @test */
  function it_requires_discountId()
  { 
    $payload = [
      'order_discounts' =>  [
        0 =>  [
          'discount_id'   =>  $this->discount->id 
        ]
      ] 
    ];

    $this->json('post', '/api/orders', $payload, $this->headers)
      ->assertStatus(422);
  }

  /** @test */
  function order_discount_saved_successfully()
  { 
    $payload = [
      'order_discounts' =>  [
        0 =>  [
          'discount_id'                =>  $this->discount->id,
          'amount'                =>  2000
        ] 
      ] 
    ];

    $this->order->addDiscounts($payload['order_discounts']);

    $this->assertCount(1, $this->order->order_discounts); 
  }

  /** @test */
  function order_discounts_updated_successfully()
  {
    $payload = [
      'order_discounts' =>  [
        0 =>  [
          'discount_id'                =>  $this->discount->id,
          'amount'                =>  2000
        ] 
      ] 
    ];

    $this->order->addDiscounts($payload['order_discounts']);

    $payload = [
      'order_discounts' =>  [
        0 =>  [
          'id'                    =>  $this->order->order_discounts[0]['id'],
          'discount_id'                =>  $this->discount->id,
          'amount'                =>  3000
        ] 
      ] 
    ];

    $this->order->updatediscounts($payload['order_discounts']);

    $this->assertEquals(3000, $this->order->order_discounts[0]['amount']);
  }

  /** @test */
  function order_discounts_removed_successfully()
  { 

    $payload = [
      'order_discounts' =>  [
        0 =>  [
          'discount_id'                =>  $this->discount->id,
          'amount'                =>  2000
        ],
        1 =>  [
          'discount_id'                =>  $this->discount->id,
          'amount'                =>  2000
        ] 
      ] 
    ];

    $this->order->addDiscounts($payload['order_discounts']);
    $this->assertCount(2, $this->order->order_discounts);

    $this->order->removeDiscounts($this->order->order_discounts[0]);
    $this->assertCount(1, $this->order->order_discounts);

  }
}
