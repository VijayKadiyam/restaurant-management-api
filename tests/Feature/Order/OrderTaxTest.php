<?php

namespace Tests\Feature\Order;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderTaxTest extends TestCase
{
  use DatabaseTransactions;

  protected $tax, $order;

  public function setUp()
  {
    parent::setUp();

    $this->tax =  factory(\App\Tax::class)->create([
      'company_id'  =>  $this->company->id
    ]);

    $this->order = factory(\App\Order::class)->create([
      'company_id'  =>  $this->company->id
    ]);

   
  }

  /** @test */
  function it_requires_taxId()
  { 
    $payload = [
      'order_taxes' =>  [
        0 =>  [
          'tax_id'   =>  $this->tax->id 
        ]
      ] 
    ];

    $this->json('post', '/api/orders', $payload, $this->headers)
      ->assertStatus(422);
  }

  /** @test */
  function order_tax_saved_successfully()
  { 
    $payload = [
      'order_taxes' =>  [
        0 =>  [
          'tax_id'                =>  $this->tax->id,
          'amount'                =>  2000
        ] 
      ] 
    ];

    $this->order->addTaxes($payload['order_taxes']);

    $this->assertCount(1, $this->order->order_taxes); 
  }

  /** @test */
  function order_taxes_updated_successfully()
  {
    $payload = [
      'order_taxes' =>  [
        0 =>  [
          'tax_id'                =>  $this->tax->id,
          'amount'                =>  2000
        ] 
      ] 
    ];

    $this->order->addTaxes($payload['order_taxes']);

    $payload = [
      'order_taxes' =>  [
        0 =>  [
          'id'                    =>  $this->order->order_taxes[0]['id'],
          'tax_id'                =>  $this->tax->id,
          'amount'                =>  3000
        ] 
      ] 
    ];

    $this->order->updateTaxes($payload['order_taxes']);

    $this->assertEquals(3000, $this->order->order_taxes[0]['amount']);
  }

  /** @test */
  function order_taxes_removed_successfully()
  { 

    $payload = [
      'order_taxes' =>  [
        0 =>  [
          'tax_id'                =>  $this->tax->id,
          'amount'                =>  2000
        ],
        1 =>  [
          'tax_id'                =>  $this->tax->id,
          'amount'                =>  2000
        ] 
      ] 
    ];

    $this->order->addTaxes($payload['order_taxes']);
    $this->assertCount(2, $this->order->order_taxes);

    $this->order->removeTaxes($this->order->order_taxes[0]);
    $this->assertCount(1, $this->order->order_taxes);

  }
}
