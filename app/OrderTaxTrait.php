<?php
namespace App;

trait OrderTaxTrait
{
  /*
   * A order has many order taxes
   *
   *@
   */
  public function order_taxes()
  {
    return $this->hasMany(OrderTax::class);
  }

  /*
   * To add a new tax
   *
   *@
   */
  public function addTaxes($taxes)
  {
    $order_taxes = [];
    foreach($taxes as $tax) { 
      $order_taxes[] = new OrderTax($tax); 
    }
    $this->order_taxes()->saveMany($order_taxes);

    return $this;
  }

  /*
   * Update  order tax
   *
   *@
   */
  public function updateTaxes($taxes)
  {
    foreach($taxes as $tax) { 
      $order_taxes = OrderTax::where('id' , '=', $tax['id'])->first(); 
      $order_taxes->update($tax);
    }  

    $this->refresh();

    return $this;
  } 

  /*
   * Remove order details
   *
   *@
   */
  public function removeTaxes($tax)
  {
    $tax instanceof OrderTax ? $id = $tax->id : $id = $tax;
    $orderTax = OrderTax::where('id', '=', $id)->first();
    $orderTax->delete();

    $this->refresh();

    return $this;
  }
}