<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
  protected $fillable = [
    'name', 'menu_category_id', 'price'
  ];

  /*
   * A product belongs to a company
   *
   *@
   */
  public function company()
  {
    return $this->belongsTo(Company::class);
  }

  /*
   * A product has many stock categories
   *
   *@
   */
  public function stock_categories()
  {
    return $this->belongsToMany(StockCategory::class, 'product_stocks', 'product_category_id', 'stock_category_id')
      ->withPivot('company_id', 'value')
      ->withTimeStamps(); 
  }

  /*
   * To store  a product category
   *
   *@
   */
  public function store()
  {
    if(request()->header('company_id')) {
      $company = Company::find(request()->header('company_id'));
      if($company)
        $company ? $company->product_categories()->save($this) : '';
    } 

    return $this;
  }

  /*
   * To add a stock category to a product category
   *
   *@
   */
  public function addStockCategory($stockCategory, $value = 0)
  { 
    return $this->stock_categories()->attach($stockCategory, [
      'company_id'  =>  $this->company_id,
      'value' =>  $value
    ]); 
  }
}
