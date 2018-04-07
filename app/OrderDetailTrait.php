<?php 
namespace App;

trait OrderDetailTrait 
{
  /*
   * A order has many order details
   *
   *@
   */
  public function order_details()
  {
    return $this->hasMany(OrderDetail::class);
  }

  /*
   * Add a order details
   *
   *@
   */
  public function addDetails($details)
  {
    $order_details = [];
    foreach($details as $detail) { 
      $order_details[] = new OrderDetail($detail); 
    }
    $this->order_details()->saveMany($order_details);

    return $this;
  }

  /*
   * Update  order details
   *
   *@
   */
  public function updateDetails($details)
  {
    foreach($details as $detail) { 
      $order_details = OrderDetail::where('id' , '=', $detail['id'])->first(); 
      $order_details->update($detail);
    }  

    $this->refresh();

    return $this;
  } 

  /*
   * Remove order details
   *
   *@
   */
  public function removeDetails($detail)
  {
    $detail instanceof OrderDetail ? $id = $detail->id : $id = $detail;
    $orderDetails = OrderDetail::where('id', '=', $id)->first();
    $orderDetails->delete();

    $this->refresh();

    return $this;
  }
}