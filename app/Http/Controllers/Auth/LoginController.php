<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Login Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles authenticating users for the application and
  | redirecting them to your home screen. The controller uses a trait
  | to conveniently provide its functionality to your applications.
  |
  */

  use AuthenticatesUsers;

  /**
   * Where to redirect users after login.
   *
   * @var string
   */
  protected $redirectTo = '/home';

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('guest')->except('logout');
  }

  /*
   * Custom handler to handle the login request
   *
   *@
   */
  public function login(Request $request)
  {
    $this->validateLogin($request);

    if($this->attemptLogin($request)) {

      $user = $this->guard()->user();
      $user->generateToken();

      return response()->json([
        'data'    =>  $user->toArray(),
        'message' =>  'User is logged in successfully'
      ]);
    }

    return $this->sendFailedLoginResponse($request);

  }

  /*
   * When the user is logged out
   *
   *@
   */
  public function logout()
  {
    $user = \Auth::guard('api')->user();

    if($user)
    {
      $user->api_token = null;
      $user->save();

      return response()->json([
        'message' =>  'User is logged out successfully'
      ], 200);
    }

    return response()->json([
      'message' =>  'User is not logged in'
    ], 204);
  }
}
