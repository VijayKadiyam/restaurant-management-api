<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/*Models*/
use App\User;

class UsersController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  /*
   * To get all the employees
   *
   *@
   */
  public function index()
  {
    $employees = \Auth::guard('api')->user()->employees;

    return response()->json([
      'data'  =>  $employees
    ], 200);
  }

  /*
   * To store a new user
   *
   *@
   */
  public function store(Request $request)
  {
    $request->validate([
      'name'        => 'required|string|max:255',
      'email'       => 'required|string|email|max:255|unique:users',
      'password'    => 'required|string|min:6|confirmed' 
    ]);

    $user = User::store(); 

    return response()->json([
      'data'  =>  $user->toArray()
    ], 201);
  }

  /*
   * To get a single user
   *
   *@
   */
  public function show(User $user)
  {
    return response()->json([
      'data'  =>  $user->toArray()
    ], 200);
  }

  /*
   * To update user details
   *
   *@
   */
  public function update(Request $request, User $user)
  {
    $request->validate([
      'name'        => 'required|string|max:255' 
    ]);

    $user->update($request->only(['name']));

    return response()->json([
      'data'  =>  $user->toArray()
    ], 200);
  }
}
