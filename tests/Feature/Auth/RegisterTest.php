<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterTest extends TestCase
{
  use DatabaseTransactions;

  /** @test */
  function it_requires_name_email_password_and_password_confirmation()
  {
    $this->json('POST', '/api/register')
      ->assertStatus(422);
  }

  /** @test */
  function user_is_registered_successfully()
  {
    $payload = [
      'name'                  =>  'Vijay',
      'email'                 =>  'email@email.com',
      'password'              =>  '12345678',
      'password_confirmation' =>  '12345678'
    ];

    $this->json('POST', 'api/register', $payload)
      ->assertStatus(201)
      ->assertJsonStructure([
        'data'  =>  [
          'name',
          'email',
          'api_token'
        ]
      ]);
  }
}
