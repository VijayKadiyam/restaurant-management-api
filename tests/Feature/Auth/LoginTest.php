<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
  use DatabaseTransactions;

  /** @test */
  function it_requires_email_and_password()
  {
    $this->json('POST','/api/login')
      ->assertStatus(422);
  }

  /** @test */
  function user_is_logged_in_successfully()
  {
    $user = factory(\App\User::class)->create([
      'email'     =>  'email@email.com',
      'password'  =>  bcrypt('123456')
    ]);

    $payload = [ 
      'email'     =>  'email@email.com',
      'password'  =>  '123456'
    ];

    $this->json('POST', '/api/login', $payload)
      ->assertStatus(200)
      ->assertJsonStructure([
        'data'  =>  [ 
          'email',
          'api_token'
        ]
      ]); 
  }
}
