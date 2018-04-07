<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  use OrderDetailTrait, OrderTaxTrait, OrderDiscountTrait;

  /*
   * A order belongs to a company
   *
   *@
   */
  public function company()
  {
    return $this->belongsTo(Company::class);
  }

  /*
   * To store a new order
   *
   *@
   */
  public function store()
  {
    if(request()->header('company_id')) {
      $company = Company::find(request()->header('company_id'));
      if($company)
        $company ? $company->orders()->save($this) : '';
    } 

    return $this;
  }  
}
