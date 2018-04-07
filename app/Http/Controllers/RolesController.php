<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/*Models*/
use App\Role;
use App\Company;

class RolesController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  /*
   * To get all the roles of a company
   *
   *@
   */
  public function index()
  {
    $company = Company::where('id', '=', request()->header('company_id'))->first();
    if($company)
      $roles = $company->roles;
    else
      $roles = "";

    return response()->json([
      'data'  =>  $roles
    ], 200);
  }

  /*
   * To store a new role
   *
   *@
   */
  public function store(Request $request)
  {
    $request->validate([
      'role'  =>  'required'
    ]);

    $role = new Role($request->all());
    $role->store();

    return response()->json([
      'data'  =>  $role->toArray()
    ], 201);  
  }

  /*
   * To get a single role
   *
   *@
   */
  public function show(Role $role)
  {
    return response()->json([
      'data'  =>  $role->toArray()
    ], 200);
  }

  /*
   * To update a role
   *
   *@
   */
  public function update(Request $request, Role $role)
  {
    $request->validate([
      'role'  =>  'required'
    ]);

    $role->update($request->all());

    return response()->json([
      'data'  =>  $role->toArray()
    ], 200);
  }
}
