<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/*Models*/
use App\Stock;
use App\Company;

class StocksController extends Controller
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
      $stocks = $company->stocks;
    else
      $stocks = "";

    return response()->json([
      'data'  =>  $stocks
    ], 200);
  }

  /*
   * To store a new stock
   *
   *@
   */
  public function store(Request $request)
  {
    $request->validate([
      'supplier_id'       =>  'required',
      'stock_category_id' =>  'required',
      'price'             =>  'required',
      'qty'               =>  'required'
    ]);

    $stock = new Stock($request->all());
    $stock->store();

    return response()->json([
      'data'  =>  $stock->toArray()
    ], 200);  
  }

  /*
   * To show a stock
   *
   *@
   */
  public function show(Stock $stock)
  {
    return response()->json([
      'data'  =>  $stock->toArray()
    ], 200);
  }

  /*
   * To update a stock
   *
   *@
   */
  public function update(Request $request, Stock $stock)
  {
    $stock->update($request->all());

    return response()->json([
      'data'  =>$stock->toArray()
    ], 200);
  }
}
