<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockCategory extends Model
{
  protected $fillable = [
    'name', 'unit_id'
  ];

  /*
   * It belongs to company
   *
   *@
   */
  public function company()
  {
    return $this->belongsTo(Company::class);
  }

  /*
   * To store a new stock catoegory
   *
   *@
   */
  public function store()
  {
    if(request()->header('company_id')) {
      $company = Company::find(request()->header('company_id'));
      if($company)
        $company ? $company->stock_categories()->save($this) : '';
    } 

    return $this;
  }
}
