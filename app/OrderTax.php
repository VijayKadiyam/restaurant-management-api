<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderTax extends Model
{
  protected $fillable = [
    'tax_id', 'amount'
  ];

  /*
   * A order tax belongs to order
   *
   *@
   */
  public function order()
  {
    return $this->belongsTo(Order::class);
  }
}
