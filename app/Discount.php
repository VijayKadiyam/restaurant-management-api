<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
  protected $fillable = [
    'name', 'discount_percent'
  ];

  /*
   * A discount belongs to a company
   *
   *@
   */
  public function company()
  {
    $this->belongsTo(Company::class);
  }

  /*
   * To store a new discount
   *
   *@
   */
  public function store()
  {
    if(request()->header('company_id')) {
      $company = Company::find(request()->header('company_id'));
      if($company)
        $company ? $company->discounts()->save($this) : '';
    } 

    return $this;
  }
}
