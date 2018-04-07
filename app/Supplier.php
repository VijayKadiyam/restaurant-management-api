<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
  protected $fillable = [
    'name', 'email', 'address', 'contact1', 'contact2', 'state_code', 'pan_no', 'gstn_no', 'acc_name', 'acc_no', 'ifsc_code', 'branch'
  ];

  /*
   * A supplier belongs to a company
   *
   *@
   */
  public function company()
  {
    return $this->belongsTo(Company::class);
  } 

  /*
   * To store a new supplier
   *
   *@
   */
  public function store()
  {
    if(request()->header('company_id')) {
      $company = Company::find(request()->header('company_id'));
      if($company)
        $company ? $company->suppliers()->save($this) : '';
    } 

    return $this;
  }
}
