<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
  use DatabaseTransactions;

  /** @test */
  function user_must_be_logged_in()
  {
    $this->json('post', '/api/users')
      ->assertStatus(401);
  }

  /** @test */
  function it_requires_name_email_password_and_password_confirmation()
  {
    $this->json('post', '/api/users', [], $this->headers)
      ->assertStatus(422);
  }

  public function users_are_fetched_successfully()
  {
    $user = factory(\App\User::class)->create([
      'name'  =>  'Vijay',
      'email' =>  'vjfrnd@gmail.com',
      'password'  =>  bcrypt('123456')
    ]); 
    $user->addAsEmployeeTo($this->user);
    $this->user->assignCompany($this->company);

    $this->json('get', '/api/users', [], $this->headers)
      ->assertStatus(200)
      ->assertJsonStructure([
        'data'  =>  [
          0 =>  [
            'name',
            'email'
          ]
        ] 
      ]);

    $this->assertCount(1, $this->user->employees);
  }

  /** @test */
  function user_saved_successfully()
  {
    $this->disableEH();

    $payload = [ 
      'name'  =>  'vijay',
      'email' =>  'vjfrnd@gmail.com',
      'password'  =>  '123456',
      'password_confirmation'  =>  '123456'
    ];

    $this->assertCount(1, $this->company->users);

    $this->json('post', '/api/users', $payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'name'  =>  'vijay',
          'email' =>  'vjfrnd@gmail.com' 
        ]
      ]);

    $payload['email'] =  'vjfrnd1@gmail.com';

    $this->json('post', '/api/users', $payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'name'  =>  'vijay',
          'email' =>  'vjfrnd1@gmail.com' 
        ]
      ]);

    $this->assertCount(2, $this->user->employees);

  }

  /** @test */
  function user_is_assigned_a_company()
  {
    $user = factory(\App\User::class)->create([
      'name'  =>  'Vijay',
      'email' =>  'vjfrnd@gmail.com',
      'password'  =>  bcrypt('123456')
    ]);

    $user->assignCompany($this->company->id);

    $this->assertCount(1, $user->companies);
  }

  /** @test */
  function single_user_fetched_successfully()
  {
    $user = factory(\App\User::class)->create([
      'name'  =>  'Vijay',
      'email' =>  'vjfrnd@gmail.com',
      'password'  =>  bcrypt('123456')
    ]); 
    $this->user->addAsEmployeeTo($user);

    $this->json('get', "/api/users/$user->id", [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'name'  =>  'Vijay',
          'email' =>  'vjfrnd@gmail.com'
        ]
      ]);
  }

  /** @test */
  function user_updated_successfully()
  {
    $user = factory(\App\User::class)->create([
      'name'  =>  'Vijay',
      'email' =>  'vjfrnd@gmail.com',
      'password'  =>  bcrypt('123456')
    ]); 
    $this->user->addAsEmployeeTo($user);
    $user->assignCompany($this->company->id);

    $user->name = "Ajay";

    $this->json('patch', "/api/users/$user->id", $user->toArray(), $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'name'  =>  'Ajay',
          'email' =>  'vjfrnd@gmail.com',
        ]
      ]);
  }
}
