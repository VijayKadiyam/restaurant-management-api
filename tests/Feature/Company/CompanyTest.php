<?php

namespace Tests\Feature\Company;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyTest extends TestCase
{
  use DatabaseTransactions;

  protected $user, $headers;

  public function setUp()
  {
    parent::setUp();

    $this->user = factory(\App\User::class)->create();
    $this->user->generateToken();

    $this->headers = [
      'Authorization' =>  "Bearer " . $this->user->api_token
    ];  
  }
  /** @test */
  function user_is_authorized_before_storing_the_company()
  {
    $payload = [
      'name'        =>  'name',
      'pan_no'      =>  'pan',
      'gstn_no'     =>  'gstn',
      'address'     =>  'address',
      'state_code'  =>  'code'
    ];

    $this->json('POST', '/api/companies', $payload)
      ->assertStatus(401);
  } 

  /** @test */
  function it_requires_name_pan_no_gstn_no_address_state_code()
  {
    $this->json('POST', '/api/companies', [], $this->headers)
      ->assertStatus(422);
  } 

  /** @test */
  function companies_are_fetched_successfully()
  {
    $company = factory(\App\Company::class)->create();
    $company->assignUser($this->user);  

    $this->json('GET', '/api/companies', [], $this->headers)
      ->assertStatus(200)
      ->assertJsonStructure([
        'data'  =>  [
          0 =>  [
            'name',
            'pan_no',
            'gstn_no',
            'address',
            'state_code'
          ]
        ]
      ]);

    $this->assertCount(1, $this->user->companies);
  }

  /** @test */
  function single_company_fetched_successfully()
  {
    $company = factory(\App\Company::class)->create([
      'name'  =>  'Aaibuzz'
    ]);
    $company->assignUser($this->user);

    $this->json('get', "/api/companies/$company->id", [], $this->headers)
      ->assertStatus(200)
      ->assertJsonStructure([
        'data'  =>  [
          'name',
          'pan_no',
          'gstn_no',
          'address',
          'state_code'
        ]
      ])
      ->assertJson([
        'data'  =>  [
          'name'  =>  'Aaibuzz'
        ]
      ]);
  }

  /** @test */
  function company_saved_successfully()
  {
    $payload = [
      'name'        =>  'name',
      'pan_no'      =>  'pan',
      'gstn_no'     =>  'gstn',
      'address'     =>  'address',
      'state_code'  =>  'code'
    ];

    $this->json('POST', '/api/companies', $payload, $this->headers)
      ->assertStatus(201)
      ->assertJsonStructure([
        'data'  =>  [
          'name',
          'pan_no',
          'gstn_no',
          'address',
          'state_code'
        ]
      ]);
  } 

  /** @test */
  function company_updated_successfully()
  {
    $this->disableEH();

    $company = factory(\App\Company::class)->create([
      'name'  =>  'Aaibuzz'
    ]);
    $company->assignUser($this->user);

    $company->name = 'Tantram';

    $this->json('patch', '/api/companies/1', $company->toArray(), $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'name'  =>  'Tantram'
        ]
      ]);
  }
}
