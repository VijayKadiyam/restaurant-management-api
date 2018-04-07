<?php

namespace Tests\Feature\Order;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrederDetailTest extends TestCase
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
  function it_requires_productCategoryId_qty_amount()
  { 
    $payload = [
      'order_details' =>  [
        0 =>  [
          'product_category_id'   =>  $this->productCategory->id,
          'qty'                   =>   3,
          'amount'                =>  (int)($this->productCategory->price) * 3
        ]
      ] 
    ];

    $this->json('post', '/api/orders', $payload, $this->headers)
      ->assertStatus(422);
  }

  /** @test */
  function order_details_saved_successfully()
  {
    $order = factory(\App\Order::class)->create([
      'company_id'  =>  $this->company->id
    ]);

    $payload = [
      'order_details' =>  [
        0 =>  [
          'product_category_id'   =>  $this->productCategory->id,
          'qty'                   =>   3,
          'amount'                =>  (int)($this->productCategory->price) * 3
        ],
        1 =>  [
          'product_category_id'   =>  $this->productCategory->id,
          'qty'                   =>   3,
          'amount'                =>  (int)($this->productCategory->price) * 3
        ]
      ] 
    ];

    $order->addDetails($payload['order_details']);

    $this->assertCount(2, $order->order_details); 
  }

  /** @test */
  function order_details_updated_successfully()
  {
    $order = factory(\App\Order::class)->create([
      'company_id'  =>  $this->company->id
    ]);

    $payload = [
      'order_details' =>  [
        0 =>  [
          'product_category_id'   =>  $this->productCategory->id,
          'qty'                   =>   3,
          'amount'                =>  (int)($this->productCategory->price) * 3
        ] 
      ] 
    ];

    $order->addDetails($payload['order_details']);

    $payload = [
      'order_details' =>  [
        0 =>  [
          'id'                    =>  $order->order_details[0]['id'],
          'product_category_id'   =>  $this->productCategory->id,
          'qty'                   =>   4,
          'amount'                =>  (int)($this->productCategory->price) * 3
        ] 
      ] 
    ];

    $order->updateDetails($payload['order_details']);

    $this->assertEquals(4, $order->order_details[0]['qty']);
  }

  /** @test */
  function order_details_removed_successfully()
  {
    $order = factory(\App\Order::class)->create([
      'company_id'  =>  $this->company->id
    ]);

    $payload = [
      'order_details' =>  [
        0 =>  [
          'product_category_id'   =>  $this->productCategory->id,
          'qty'                   =>   3,
          'amount'                =>  (int)($this->productCategory->price) * 3
        ],
        1 =>  [
          'product_category_id'   =>  $this->productCategory->id,
          'qty'                   =>   3,
          'amount'                =>  (int)($this->productCategory->price) * 3
        ]
      ] 
    ];

    $order->addDetails($payload['order_details']);
    $this->assertCount(2, $order->order_details);

    $order->removeDetails($order->order_details[0]);
    $this->assertCount(1, $order->order_details);

  }
}
