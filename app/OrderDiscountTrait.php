<?php
namespace App;

trait OrderDiscountTrait
{
  /*
   * A order has many order discounts
   *
   *@
   */
  public function order_discounts()
  {
    return $this->hasMany(OrderDiscount::class);
  }

  /*
   * To add a new discount
   *
   *@
   */
  public function addDiscounts($discounts)
  {
    $order_discounts = [];
    foreach($discounts as $discount) { 
      $order_discounts[] = new OrderDiscount($discount); 
    }
    $this->order_discounts()->saveMany($order_discounts);

    return $this;
  }

  /*
   * Update  order discount
   *
   *@
   */
  public function updateDiscounts($discounts)
  {
    foreach($discounts as $discount) { 
      $order_discounts = OrderDiscount::where('id' , '=', $discount['id'])->first(); 
      $order_discounts->update($discount);
    }  

    $this->refresh();

    return $this;
  } 

  /*
   * Remove order discounts
   *
   *@
   */
  public function removeDiscounts($discount)
  {
    $discount instanceof OrderDiscount ? $id = $discount->id : $id = $discount;
    $orderDiscount = OrderDiscount::where('id', '=', $id)->first();
    $orderDiscount->delete();

    $this->refresh();

    return $this;
  }
}