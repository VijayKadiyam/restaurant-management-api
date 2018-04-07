<?php

namespace App\Http\Controllers;

use App\Company;
use App\MenuCategory;
use Illuminate\Http\Request;

class MenuCategoriesController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  /*
   * To get all the menu categories
   *
   *@
   */
  public function index()
  {
    $company = Company::where('id', '=', request()->header('company_id'))->first();
    if($company)
      $menu_categories = $company->menu_categories;
    else
      $menu_categories = "";

    return response()->json([
      'data'  =>  $menu_categories
    ], 200);
  }

  /*
   * To store a new meny category
   *
   *@
   */
  public function store(Request $request)
  {
    $request->validate([
      'name'  =>  'required'
    ]);

    $menuCategory = new MenuCategory($request->all());
    $menuCategory->store();

    return response()->json([
      'data'  =>  $menuCategory->toArray()
    ], 201);  
  }

  /*
   * To get single menu category
   *
   *@
   */
  public function show(MenuCategory $menuCategory)
  {
    return response()->json([
      'data'  =>$menuCategory->toArray()
    ], 200);
  }

  /*
   * To update a menu category
   *
   *@
   */
  public function update(Request $request, MenuCategory $menuCategory)
  {
    $request->validate([
      'name'  =>  'required'
    ]);

    $menuCategory->update($request->all());

    return response()->json([
      'data'  => $menuCategory->toArray()
    ], 200);  
  }
}
