<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDiscount extends Model
{
  protected $fillable = [
    'discount_id', 'amount'
  ];

  /*
   * A order discount belongs to order
   *
   *@
   */
  public function order()
  {
    return $this->belongsTo(Order::class);
  }
}
