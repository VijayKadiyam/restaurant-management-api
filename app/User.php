<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'name', 'email', 'password', 'api_token'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
      'password', 'remember_token'
  ];

  /*
   * Generate the api token
   *
   *@
   */
  public function generateToken()
  {
    $this->api_token = str_random(60);
    $this->save();

    return $this;
  }

  /*
   * To store a new user
   *
   *@
   */
  public static function store()
  {
    $user = User::create([
      'name' => request()->name,
      'email' => request()->email,
      'password' => bcrypt(request()->password),
    ]);
    $user->addAsEmployeeTo(\Auth::guard('api')->user());
    $user->assignCompany(request()->header('company_id'));

    return $user;
  }

  /*
   * A user belongs to many companies
   *
   *@
   */
  public function companies()
  {
    return $this->belongsToMany(Company::class);
  } 

  /*
   * To assign a company
   *
   *@
   */
  public function assignCompany($company)
  {
    ($company instanceof Company) ? $company->id : $company;

    $this->companies()->find($company) ?  '' : $this->companies()->attach($company);

    return $this; 
  }

  /*
   * A user can have many employees
   *
   *@
   */
  public function employees()
  {
    return $this->belongsToMany(User::class, 'employee_user', 'user_id', 'employee_id');
  } 

  /*
   * Employee is added to the user
   *
   *@
   */
  public function addAsEmployeeTo($user)
  {
    return $user->employees()->attach($this);
  }
}
