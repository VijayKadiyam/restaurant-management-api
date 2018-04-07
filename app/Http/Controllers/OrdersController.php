<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  /*
   * To store a new order
   *
   *@
   */
  public function store(Request $request)
  {
    $order = new Order;
    $order->store();

    if(sizeof(request()->order_details))
    {
      $request->validate([
        'order_details.product_category_id' =>  'required',
        'order_details.qty'                 =>  'required',
        'order_details.amount'              =>  'required'  
      ]);
    }

    if(sizeof(request()->order_taxes))
    {
      $request->validate([
        'order_taxes.tax_id' =>  'required', 
        'order_taxes.amount'              =>  'required'  
      ]);
    }

    if(sizeof(request()->order_discounts))
    {
      $request->validate([
        'order_discounts.discount_id' =>  'required', 
        'order_discounts.amount'              =>  'required'  
      ]);
    }

    return response()->json([
      'data'  =>  $order->toArray()
    ], 201);
  }
}
