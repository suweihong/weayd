<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
     //支付的页面
    public function payment(Request $request)
    {
        $order_id = $request->order_id;
        $order = Order::find($order_id);
        $store = $order->store;
        $fields = $orders->fields()->get();
        foreach ($fields as $key => $field) {
           $time = $field->pivot->time;
           $place_num = $field->pivot->place_num;
           $field['time'] = $time;
           $field['place_num'] = $place_num;
        }
        return response()->json([
            'order' => $order,
            'fields' => $fields,
            'store' => $store,
        ],200);   
    }


    //支付成功的页面
    public function pay_suc()
    {
 		
    	return response()->json([
    		'errcode' => 1,
    		'errmsg' => '支付成功的页面',
    	],200);
    }
}
