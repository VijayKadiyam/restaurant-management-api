<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
  protected $fillable = [
    'name', 'tax_percent'
  ];  

  /*
   * A tax belongs to company
   *
   *@
   */
  public function company()
  {
    $this->belongsTo(Company::class);
  }

  /*
   * To store a tax
   *
   *@
   */
  public function store()
  {
    if(request()->header('company_id')) {
      $company = Company::find(request()->header('company_id'));
      if($company)
        $company ? $company->taxes()->save($this) : '';
    } 

    return $this;
  }
}
