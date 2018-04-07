<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/*Models*/
use App\StockCategory;
use App\Company;

class StockCategoriesController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  /*
   * To get all the stocks
   *
   *@
   */
  public function index()
  {
    $company = Company::where('id', '=', request()->header('company_id'))->first();
    if($company)
      $stock_categories = $company->stock_categories;
    else
      $stock_categories = "";

    return response()->json([
      'data'  =>  $stock_categories
    ], 200);
  }

  /*
   * To store a new stock category
   *
   *@
   */
  public function store(Request $request)
  {
    $request->validate([
      'name'    =>  'required',
      'unit_id' =>  'required'
    ]);

    $stockCategory = new StockCategory($request->all());
    $stockCategory->store();

    return response()->json([
      'data'  =>$stockCategory->toArray()
    ], 201);
  }

  /*
   * To get a single stock category
   *
   *@
   */
  public function show(StockCategory $stockCategory)
  {
    return response()->json([
      'data'  =>  $stockCategory->toArray()
    ], 200);
  }

  /*
   * To update a stock category
   *
   *@
   */
  public function update(Request $request, StockCategory $stockCategory)
  { 
    $request->validate([
      'name'    =>  'required',
      'unit_id' =>  'required'
    ]);
    
    $stockCategory->update($request->all());

    return response()->json([
      'data'  =>  $stockCategory->toArray()
    ], 200);
  }
}
