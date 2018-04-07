<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
  protected $fillable = [
    'supplier_id', 'stock_category_id', 'price', 'qty'
  ];

  /*
   * A stock belongs to company
   *
   *@
   */
  public function company()
  {
    return $this->belongsTo(Company::class);
  }

  /*
   * To store a new stock
   *
   *@
   */
  public function store()
  {
    if(request()->header('company_id')) {
      $company = Company::find(request()->header('company_id'));
      if($company)
        $company ? $company->stocks()->save($this) : '';
    } 

    return $this;
  }
}
