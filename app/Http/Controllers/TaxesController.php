<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/*Models*/
use App\Tax;
use App\Company;

class TaxesController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  /*
   * To get all the taxes
   *
   *@
   */
  public function index()
  {
    $company = Company::where('id', '=', request()->header('company_id'))->first();
    if($company)
      $taxes = $company->taxes;
    else
      $taxes = "";

    return response()->json([
      'data'  =>  $taxes
    ], 200);
  }

  /*
   * To store a new tax
   *
   *@
   */
  public function store(Request $request)
  {
    $request->validate([
      'name'  =>  'required',
      'tax_percent' =>  'required'
    ]);

    $tax = new Tax($request->all());
    $tax->store();

    return response()->json([
      'data'  =>  $tax->toArray()
    ], 201);
  }

  /*
   * To fetch a single tax
   *
   *@
   */
  public function show(Tax $tax)
  {
    return response()->json([
      'data' =>  $tax->toArray()
    ], 200);
  }

  /*
   * To update a tax details
   *
   *@
   */
  public function update(Request $request, Tax $tax)
  {
    $request->validate([
      'name'  =>  'required',
      'tax_percent' =>  'required'
    ]); 
    $tax->update($request->all());

    return response()->json([
      'data'  =>  $tax->toArray()
    ], 200);
  }
}
