<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuCategory extends Model
{
  protected $fillable = [
    'name'
  ];

  /*
   * A menu category belongs to a company
   *
   *@
   */
  public function company()
  {
    return $this->belongsTo(Company::class);
  }

  /*
   * To store a new menu category
   *
   *@
   */
  public function store()
  {
    if(request()->header('company_id')) {
      $company = Company::find(request()->header('company_id'));
      if($company)
        $company ? $company->menu_categories()->save($this) : '';
    } 

    return $this;
  }
}
