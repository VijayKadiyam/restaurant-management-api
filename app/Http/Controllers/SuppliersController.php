<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/*Models*/
use App\Company;
use App\Supplier;

class SuppliersController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  /*
   * Get all the suppliers of a company
   *
   *@
   */
  public function index()
  {
    $company = Company::where('id', '=', request()->header('company_id'))->first();
    if($company)
      $suppliers = $company->suppliers;
    else
      $suppliers = "";

    return response()->json([
      'data'  =>  $suppliers
    ], 200);
  }
  
  /*
   * To store a new supplier
   *
   *@
   */
  public function store(Request $request)
  {
    $request->validate([ 
      'name'        =>  'required',
      'email'       =>  'required',
      'address'     =>  'required',
      'contact1'    =>  'required',
      'state_code'  =>  'required',
      'gstn_no'     =>  'required'
    ]);

    $supplier = new Supplier($request->all());
    $supplier->store();

    return response()->json([
      'data'  =>  $supplier->toArray()
    ], 201);
  }

  /*
   * To get a single supplier
   *
   *@
   */
  public function show(Supplier $supplier)
  {
    return response()->json([
      'data'  => $supplier->toArray()
    ], 200);
  }

  /*
   * To update a supplier details
   *
   *@
   */
  public function update(Request $request, Supplier $supplier)
  {
    $request->validate([ 
      'name'        =>  'required',
      'email'       =>  'required',
      'address'     =>  'required',
      'contact1'    =>  'required',
      'state_code'  =>  'required',
      'gstn_no'     =>  'required'
    ]);
    
    $supplier->update($request->all());

    return response()->json([
      'data'  =>  $supplier->toArray()
    ]);
  }
}
