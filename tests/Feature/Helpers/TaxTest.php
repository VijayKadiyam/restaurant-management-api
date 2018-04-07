<?php

namespace Tests\Feature\Helpers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaxTest extends TestCase
{
  /** @test */
  function user_must_be_logged_in()
  {
    $this->json('post', '/api/taxes')
      ->assertStatus(401); 
  }

  /** @test */
  function it_requires_name_and_taxPercent()
  {
    $this->json('post', '/api/taxes', [], $this->headers)
      ->assertStatus(422); 
  }

  /** @test */
  function taxes_fetched_successfully()
  {
    factory(\App\Tax::class)->create([
      'company_id'  =>  $this->company->id,
      'name'        =>  'GST',
      'tax_percent' =>  '18'
    ]);

    $this->json('get', '/api/taxes', [], $this->headers)
      ->assertStatus(200) 
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'name'        =>  'GST',
            'tax_percent' =>  '18'
          ]
        ]
      ]);
  }

  /** @test */
  function tax_stored_successfully()
  {
    $payload = [
      'name'  =>  'GST18',
      'tax_percent' =>  '18'
    ];

    $this->json('post', '/api/taxes', $payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'name'  =>  'GST18',
          'tax_percent' =>  '18'
        ]
      ]);
  } 

  /** @test */
  function single_tax_fetched_successfully()
  {
    $tax = factory(\App\Tax::class)->create([
      'company_id'  =>  $this->company->id,
      'name'        =>  'GST',
      'tax_percent' =>  '18'
    ]);

    $this->json('get', "/api/taxes/$tax->id", [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'name'        =>  'GST',
          'tax_percent' =>  '18'
        ]
      ]);
  }

  /** @test */
  function it_requires_name_and_taxPrecent_while_updating()
  {
    $tax = factory(\App\Tax::class)->create([
      'company_id'  =>  $this->company->id,
      'name'        =>  'GST',
      'tax_percent' =>  '18'
    ]);

    $this->json('patch', "/api/taxes/$tax->id", [], $this->headers)
      ->assertStatus(422);
  }

  /** @test */
  function tax_updated_successfully()
  {
    $tax = factory(\App\Tax::class)->create([
      'company_id'  =>  $this->company->id,
      'name'        =>  'GST',
      'tax_percent' =>  '18'
    ]);
    $tax->tax_percent = '20';

    $this->json('patch', "/api/taxes/$tax->id", $tax->toArray(), $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'name'        =>  'GST',
          'tax_percent' =>  '20'
        ]
      ]);
  }
}
