<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/*Models*/
use App\Customer;
use App\Company;

class CustomersController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  /*
   * To get all the customers of a company
   *
   *@
   */
  public function index()
  {
    $company = Company::where('id', '=', request()->header('company_id'))->first();
    if($company)
      $customers = $company->customers;
    else
      $customers = "";

    return response()->json([
      'data'  =>  $customers
    ], 200);
  } 

  /*
   * To store a new customer
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

    $customer = new Customer($request->all());
    $customer->store();

    return response()->json([
      'data'  =>  $customer->toArray()
    ], 201); 
  }

  /*
   * To get a single customer
   *
   *@
   */
  public function show(Customer $customer)
  {
    return response()->json([
      'data'  =>  $customer->toArray()
    ], 200);
  }

  /*
   * To update a customer details
   *
   *@
   */
  public function update(Request $request, Customer $customer)
  {
    $request->validate([
      'name'        =>  'required',
      'email'       =>  'required',
      'address'     =>  'required',
      'contact1'    =>  'required',
      'state_code'  =>  'required',
      'gstn_no'     =>  'required'
    ]);
    
    $customer->update($request->all());

    return response()->json([
      'data'  =>  $customer->toArray()
    ], 200);  
  }
}
