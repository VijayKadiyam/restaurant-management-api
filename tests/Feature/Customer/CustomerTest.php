<?php

namespace Tests\Feature\Customer;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CustomerTest extends TestCase
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
      'Authorization' =>  "Bearer ". $this->user->api_token,
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
  function user_must_be_logged_in_before_accessing_the_controller()
  {
    $this->json('post', '/api/customers')
      ->assertStatus(401); 
  }

  /** @test */
  function it_requires_name_email_address_contact1_state_code_gstn_no()
  {
    $this->json('post', '/api/customers', [], $this->headers)
      ->assertStatus(422);
  }

  /** @test */
  function customers_are_fetched_successfully()
  {
    factory(\App\Customer::class)->create([
      'company_id'  =>  $this->company->id
    ]);

    factory(\App\Customer::class)->create([
      'company_id'  =>  $this->company->id
    ]);

    $this->json('get', '/api/customers', [], $this->headers)
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

      $this->assertCount(2, $this->company->customers);
  }

  /** @test */
  function customer_addded_successfully()
  {
    $this->json('post', '/api/customers', $this->payload, $this->headers)
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
  function single_customer_fetched_successfully()
  {
    $customer = factory(\App\Customer::class)->create([
      'company_id'  =>  $this->company->id,
      'name'        =>  'Tantram' 
    ]);

    $this->json('get', "/api/customers/$customer->id", [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'name'  =>  'Tantram'
        ]
      ]);
  }

  /** @test */
  function it_requires_name_email_address_contact1_state_code_gstn_no_while_updating()
  {
    $customer = factory(\App\Customer::class)->create([
      'company_id'  =>  $this->company->id,
      'name'        =>  'Tantram' 
    ]);
    $customer->name = "Aaibuzz";
    
    $this->json('patch', "/api/customers/$customer->id", [], $this->headers)
      ->assertStatus(422);
  }

  /** @test */
  function customer_updated_successfully()
  {
    $customer = factory(\App\Customer::class)->create([
      'company_id'  =>  $this->company->id,
      'name'        =>  'Tantram' 
    ]);
    $customer->name = "Aaibuzz";

    $this->json('patch', "/api/customers/$customer->id", $customer->toArray(), $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'name'  =>  'Aaibuzz'
        ]
      ]);
  }
}
