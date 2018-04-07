<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/*Models*/
use App\Discount;
use App\Company;

class DiscountsController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  /*
   * To get all the discounts of a company
   *
   *@
   */
  public function index()
  {
    $company = Company::where('id', '=', request()->header('company_id'))->first();
    if($company)
      $discounts = $company->discounts;
    else
      $discounts = "";

    return response()->json([
      'data'  =>  $discounts
    ], 200);    
  }

  /*
   * To store a new discount percent
   *
   *@
   */
  public function store(Request $request)
  {
    $request->validate([
      'name'  =>  'required',
      'discount_percent'  =>  'required'
    ]);

    $discount = new Discount($request->all());
    $discount->store();

    return response()->json([
      'data'  =>  $discount->toArray()
    ], 201);
  }

  /*
   * To get a single discount
   *
   *@
   */
  public function show(Discount $discount)
  {
    return response()->json([
      'data'  =>  $discount->toArray()
    ], 200);
  }

  /*
   * To update a discount
   *
   *@
   */
  public function update(Request $request, Discount $discount)
  {
    $request->validate([
      'name'  =>  'required',
      'discount_percent'  =>  'required'
    ]);

    $discount->update($request->all());

    return response()->json([
      'data'  =>  $discount->toArray()
    ], 200);
  }
}
