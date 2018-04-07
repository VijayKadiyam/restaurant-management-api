<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
  protected $fillable = [
    'unit'
  ];  

  /*
   * A unit belongs to company
   *
   *@
   */
  public function company()
  {
    return $this->belongsTo(Company::class);
  }

  /*
   * To store a new unit
   *
   *@
   */
  public function store()
  {
    if(request()->header('company_id')) {
      $company = Company::find(request()->header('company_id'));
      if($company)
        $company ? $company->units()->save($this) : '';
    } 

    return $this;
  }
}
