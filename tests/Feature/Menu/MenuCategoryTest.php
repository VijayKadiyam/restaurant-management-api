<?php

namespace Tests\Feature\Menu;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MenuCategoryTest extends TestCase
{
  /** @test */
  function user_must_be_logged_in()
  {
    $this->json('post', '/api/menu-categories')
      ->assertStatus(401);
  }  

  /** @test */
  function it_requires_name()
  {
    $this->json('post', '/api/menu-categories', [], $this->headers)
      ->assertStatus(422);
  }

  /** @test */
  function menu_categories_fetched_successfully()
  {
    $this->disableEH();

    factory(\App\MenuCategory::class)->create([
      'company_id'  =>  $this->company->id,
      'name'        =>  'Desert'
    ]);

    $this->json('get', '/api/menu-categories', [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'name'  =>  'Desert'
          ]
        ]
      ]);
  }

  /** @test */
  function menu_category_saved_successfully()
  {
    $payload = [
      'name'  =>  'Vijay'
    ];

    $this->json('post', '/api/menu-categories', $payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'name'  =>  'Vijay'
        ]
      ]); 

  } 

  /** @test */
  function single_menu_category_fetched_successfully()
  {
    $menuCategory = factory(\App\MenuCategory::class)->create([
      'company_id'  =>  $this->company->id,
      'name'        =>  'Desert'
    ]);

    $this->json('get', "/api/menu-categories/$menuCategory->id", [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'name'  =>  'Desert'
        ]
      ]);
  }

  /** @test */
  function it_requires_name_while_updating()
  {
    $menuCategory = factory(\App\MenuCategory::class)->create([
      'company_id'  =>  $this->company->id,
      'name'        =>  'Desert'
    ]);
    $menuCategory->name = 'Main Course';

    $this->json('patch', "/api/menu-categories/$menuCategory->id", [], $this->headers)
      ->assertStatus(422);
  }

  /** @test */
  function menu_category_updated_successfully()
  {
    $menuCategory = factory(\App\MenuCategory::class)->create([
      'company_id'  =>  $this->company->id,
      'name'        =>  'Desert'
    ]);
    $menuCategory->name = 'Main Course';

    $this->json('patch', "/api/menu-categories/$menuCategory->id", $menuCategory->toArray(), $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'name'  =>  'Main Course'
        ]
      ]);
  }
}
