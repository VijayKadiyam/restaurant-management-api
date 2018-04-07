<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/*Models*/
use App\Unit;
use App\Company;

class UnitsController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  /*
   * To get all the units
   *
   *@
   */
  public function index()
  {
    $company = Company::where('id', '=', request()->header('company_id'))->first();
    if($company)
      $units = $company->units;
    else
      $units = "";

    return response()->json([
      'data'  =>  $units
    ], 200);
  }

  /*
   * To store a new unit
   *
   *@
   */
  public function store(Request $request)
  {
    $request->validate([
      'unit'  =>  'required'
    ]); 

    $unit = new Unit($request->all());
    $unit->store();

    return response()->json([
      'data'  =>  $unit->toArray()
    ], 201);
  }

  /*
   * To get a single unit
   *
   *@
   */
  public function show(Unit $unit)
  {
    return response()->json([
      'data'  =>  $unit->toArray()
    ], 200);
  }

  /*
   * To update a unit
   *
   *@
   */
  public function update(Request $request, Unit $unit)
  {
    $request->validate([
      'unit'  =>  'required'
    ]);

    $unit->update($request->all());

    return response()->json([
      'data'  =>  $unit->toArray()
    ], 200);
  }
}
