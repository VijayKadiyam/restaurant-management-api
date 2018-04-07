<?php

namespace Tests\Feature\Supplier;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SupplierTest extends TestCase
{
  use DatabaseTransactions;

  protected $user, $headers, $payload, $company;

  public function setUp()
  {
    parent::setUp();
    
    $this->user = factory(\App\User::class)->create();
    $this->user->generateToken();

    $this->company = factory(\App\Company::class)->create();

    $this->headers = [
      'Authorization' =>  "Bearer " . $this->user->api_token,
      'company_id'    =>  $this->company->id
    ]; 

    $this->payload = [ 
      'name'        =>  'name',
      'email'       =>  'email',
      'address'     =>  'address',
      'contact1'    =>  'contact1',
      'state_code'  =>  '27',
      'gstn_no'     =>  'no'
    ];
  }

  /** @test */
  function user_is_authorized_before_storing_the_supplier()
  { 
    $this->json('post', '/api/suppliers', $this->payload)
      ->assertStatus(401);
  }

  /** @test */
  function it_requires_name_email_address_contact1_state_code_gstn_no()
  {
    $this->json('post', '/api/suppliers', [], $this->headers)
      ->assertStatus(422);
  }

  /** @test */
  function suppliers_are_fetched_successfully()
  {
    $this->disableEH();

    factory(\App\Supplier::class)->create([
      'company_id'  =>  $this->company->id
    ]);

    factory(\App\Supplier::class)->create([
      'company_id'  =>  $this->company->id
    ]);

    $this->json('get', '/api/suppliers', [], $this->headers)
      ->assertStatus(200)
      ->assertJsonStructure([
        'data'  =>  [
          0 =>  [
            'name',
            'email',
            'address'
          ]
        ]
      ]);

    $this->assertCount(2, $this->company->suppliers);
  }
  
  /** @test */
  function supplier_is_added_successfully()
  {

    $this->json('post', '/api/suppliers', $this->payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [ 
          'name'        =>  'name',
          'email'       =>  'email',
          'address'     =>  'address',
          'contact1'    =>  'contact1',
          'state_code'  =>  '27',
          'gstn_no'     =>  'no'
        ]
      ]);
  }

  /** @test */
  function single_supplier_fetched_successfully()
  {
    $supplier = factory(\App\Supplier::class)->create([
      'company_id'  =>  $this->company->id,
      'name'  =>  'Aaibuzz'
    ]);

    $this->json('get', "/api/suppliers/$supplier->id", [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'name'  =>  'Aaibuzz'
        ]
      ]);
  }

  /** @test */
  function it_requires_name_email_address_contact1_state_code_gstn_no_while_updating()
  {
    $supplier = factory(\App\Supplier::class)->create([
      'company_id'  =>  $this->company->id,
      'name'  =>  'Aaibuzz'
    ]);
    $supplier->name = "Tantram";
    
    $this->json('patch', "/api/suppliers/$supplier->id", [], $this->headers)
      ->assertStatus(422);
  }

  /** @test */
  function supplier_updated_successfully()
  {
    $supplier = factory(\App\Supplier::class)->create([
      'company_id'  =>  $this->company->id,
      'name'  =>  'Aaibuzz'
    ]);
    $supplier->name = "Tantram";

    $this->json('patch', "/api/suppliers/$supplier->id", $supplier->toArray(), $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'name'  =>  'Tantram'
        ]
      ]);
  }
}
