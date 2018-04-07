<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
  protected $fillable = [
    'product_category_id', 'qty', 'amount'
  ];

  /*
   * A order detail belongs to a order
   *
   *@
   */
  public function order()
  {
    return $this->belongsTo(Order::class);
  }
}
