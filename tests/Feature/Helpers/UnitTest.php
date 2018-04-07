<?php

namespace Tests\Feature\Helpers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UnitTest extends TestCase
{ 
  use DatabaseTransactions;

  /** @test */
  function user_must_be_logged_in()
  {
    $this->json('post', '/api/units')
      ->assertStatus(401);
  }

  /** @test */
  function it_requires_company_id_in_the_header_and_unit()
  {
    $this->json('post', '/api/units', [], $this->headers)
      ->assertStatus(422); 
    
  }

  /** @test */
  function units_fetched_successfully()
  {
    $this->disableEH();

    factory(\App\Unit::class)->create([
      'company_id'  =>  $this->company->id
    ]);

    factory(\App\Unit::class)->create([
      'company_id'  =>  $this->company->id
    ]);

    $this->json('get', 'api/units', [], $this->headers)
      ->assertStatus(200)
      ->assertJsonStructure([
        'data'  =>  [
          0 =>  [
            'unit'
          ]
        ]
      ]);

    $this->assertCount(2, $this->company->units);
  }

  /** @test */
  function unit_saved_successfully()
  {
    $payload = [
      'unit'  =>  'Kg'
    ];

    $this->json('post', '/api/units', $payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'unit'  =>  'Kg'
        ]
      ]); 
  }

  /** @test */
  function unit_is_fetched_successfully()
  {
    $unit = factory(\App\Unit::class)->create([
      'company_id'  =>  $this->company->id,
      'unit'  =>  'Kg'
    ]);

    $this->json('get', "/api/units/$unit->id", [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'unit'  =>  'Kg'
        ]
      ]);
  }

  /** @test */
  function it_requires_company_id_in_the_header_and_unit_while_updating()
  {
    $unit = factory(\App\Unit::class)->create([
      'company_id'  =>  $this->company->id,
      'unit'  =>  'Kg'
    ]);
    $unit->unit = 'Grams';

    $this->json('patch', "/api/units/$unit->id", [], $this->headers)
      ->assertStatus(422);
  }

  /** @test */
  function unit_is_updated_successfully()
  {
    $unit = factory(\App\Unit::class)->create([
      'company_id'  =>  $this->company->id,
      'unit'  =>  'Kg'
    ]);
    $unit->unit = 'Grams';

    $this->json('patch', "/api/units/$unit->id", $unit->toArray(), $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'unit'  =>  'Grams'
        ]
      ], 200);
  }
}
