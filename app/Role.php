<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
  protected $fillable = [
    'role'
  ];

  /*
   * A role belongs to a company
   *
   *@
   */
  public function company()
  {
    $this->belongsTo(Company::class);
  }

  /*
   * To store a new role
   *
   *@
   */
  public function store()
  {
    if(request()->header('company_id')) {
      $company = Company::find(request()->header('company_id'));
      if($company)
        $company ? $company->roles()->save($this) : '';
    } 

    return $this;
  }
}
