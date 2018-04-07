<?php

namespace Tests\Feature\Helpers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RolesTest extends TestCase
{
  use DatabaseTransactions;

  /** @test */
  function user_must_be_logged_in()
  {
    $this->json('post', '/api/roles') 
      ->assertStatus(401);
  }

  /** @test */
  function it_requires_role()
  {
    $this->json('post', '/api/roles', [], $this->headers)
      ->assertStatus(422); 
  }

  /** @test */
  function roles_fetched_successfully()
  {
    factory(\App\Role::class)->create([
      'company_id'  =>  $this->company->id,
      'role'        =>  'User'
    ]);

    $this->json('get', '/api/roles', [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'role'  =>  'User'
          ]
        ]
      ]);

    $this->assertCount(1, $this->company->roles);
  }

  /** @test */
  function role_saved_successfully()
  {
    $payload = [
      'role'  =>  'User'
    ];

    $this->json('post', '/api/roles', $payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'role'  =>  'User'
        ]
      ]);
  }

  /** @test */
  function single_role_fetched_successfully()
  {
    $role = factory(\App\Role::class)->create([
      'company_id'  =>  $this->company->id,
      'role'        =>  'User'
    ]);

    $this->json('get', "/api/roles/$role->id", [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'role'  =>  'User'
        ]
      ]); 
  }

  /** @test */
  function it_requires_role_while_updating()
  {
    $role = factory(\App\Role::class)->create([
      'company_id'  =>  $this->company->id,
      'role'        =>  'User'
    ]);

    $this->json('patch', "/api/roles/$role->id", [], $this->headers)
      ->assertStatus(422);
  }

  /** @test */
  function role_updated_successfully()
  {
    $role = factory(\App\Role::class)->create([
      'company_id'  =>  $this->company->id,
      'role'        =>  'User'
    ]);
    $role->role = "Admin";

    $this->json('patch', "/api/roles/$role->id", $role->toArray(), $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'role'  =>  'Admin'
        ]
      ]);
  }
}
