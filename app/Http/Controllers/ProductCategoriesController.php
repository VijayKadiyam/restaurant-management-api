<?php

namespace App\Http\Controllers;

use App\Company;
use App\StockCategory;
use App\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoriesController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  /*
   * To get all the product categories
   *
   *@
   */
  public function index()
  {
    $company = Company::where('id', '=', request()->header('company_id'))->first();
    if($company)
      $productCategories = $company->product_categories;
    else
      $productCategories = "";

    return response()->json([
      'data'  =>  $productCategories
    ], 200);
  }

  /*
   * To store a product category
   *
   *@
   */
  public function store(Request $request)
  {
    $request->validate([
      'menu_category_id'  =>  'required',
      'name'              =>  'required',
      'price'             =>  'required'
    ]);

    $productCategory = new ProductCategory($request->all());
    $productCategory->store();

    $stockCategories = [];
    if($request->stock_categories) {
      foreach($request->stock_categories as $stockCategory) { 
        $category = StockCategory::where('id', '=', $stockCategory['id'])->first();
        $productCategory->addStockCategory($category, $stockCategory['value']);
      }
    } 

    return response()->json([
      'data'  => $productCategory->toArray()
    ], 201);  
  }

  /*
   * To fetch a single product
   *
   *@
   */
  public function show(ProductCategory $productCategory)
  {
    return response()->json([
      'data'  =>  $productCategory->toArray()
    ], 200);
  }

  /*
   * To update a single product
   *
   *@
   */
  public function update(Request $request, ProductCategory $productCategory)
  {
    $request->validate([
      'name'  =>  'required'
    ]);

    $productCategory->update($request->all()); 

    return response()->json([
      'data'  =>  $productCategory->toArray()
    ], 200);
  }

  
}
