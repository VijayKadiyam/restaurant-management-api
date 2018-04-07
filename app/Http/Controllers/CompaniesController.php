<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Models
use App\Company;

class CompaniesController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  /*
   * To get all the companies of the authorized user
   *
   *@
   */
  public function index()
  {
    $companies = \Auth::guard('api')->user()->companies;

    return response()->json([
      'data'  =>  $companies
    ], 200);
  }

  /*
   * To view a single company
   *
   *@
   */
  public function show(Company $company)
  {
    return response()->json([
      'data'  =>  $company->toArray()
    ], 200);    
  }

  /*
   * To store a new company
   *
   *@
   */
  public function store(Request $request)
  {
    $request->validate([
      'name'        =>  'required',
      'pan_no'      =>  'required',
      'gstn_no'     =>  'required',
      'address'     =>  'required',
      'state_code'  =>  'required'
    ]);

    // To store a company and assign a user
    $company = new Company($request->all());
    $company->store(); 

    return response()->json([
      'data'  =>  $company->toArray()
    ], 201);
  }

  /*
   * To update a company details
   *
   *@
   */
  public function update(Request $request, Company $company)
  {
    $request->validate([
      'name'        =>  'required',
      'pan_no'      =>  'required',
      'gstn_no'     =>  'required',
      'address'     =>  'required',
      'state_code'  =>  'required'
    ]);

    $company->update($request->all());

    return response()->json([
      'data'  =>  $company->toArray()
    ], 200);
  }
}
