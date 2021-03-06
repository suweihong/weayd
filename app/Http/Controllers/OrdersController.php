<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Field;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Field_order;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //我的订单 列表
    public function index(Request $request)
    {
        
        // $now = date('Y-m-d H:i:s',time());
        // dd($now);
        $client_id = $request->client_id;
        $orders = Order::where('client_id',$client_id)->orderBy('created_at','desc')->get();
        $order_list = [];
        foreach ($orders as $key => $order) {
            $store = $order->store->title;//店铺名称
            $total = $order->total;//订单价格
            $time = strtotime($order->created_at);//下单时间
            $week = date('N',$time);//下单时间为 周几
            $date = (string)$order->created_at;
            $status_id = $order->status_id;//订单状态
            $order_list[$key] = [
                'store_title'=>$store,
                'total' => $total,
                'week' => $week,
                'date' => $date,
                'status_id' => $status_id,
                ];
                // dump($order_list);
           
           
           
        }
        // dump($order_list);
        return response()->json([
            'errcode' => 1,
            'orders' => $order_list,
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //添加订单
    public function store(Request $request)
    {
        $week = $request->week;
        $date = $request->date;
        // $payment_id = $request->pay_id;
        $type_id = $request->type_id;
        $store_id = $request->store_id;
        $item_id = $request->item_id;
        $client_id = $request->client_id;
        // $phone = $request->phone;
        // $fields = [
        //     0 => [ 'field_id'=>1286,
        //            'place_id'=>64,
        //            'place_num'=>2,
        //            'time'=>2,
        //            'price'=>44],
        //     1 => [ 'field_id'=>1300,
        //            'place_id'=>64,
        //            'place_num'=>2,
        //            'time'=>4,
        //            'price'=>33 ],
        //     2 => [ 'field_id'=>1660,
        //            'place_id'=>12,
        //            'place_num'=>1,
        //            'time'=>5,
        //            'price'=>55 ],


        // ];


            //开启事务
        DB::beginTransaction();
            //改变商品状态
        $total = 0; //订单金额
        foreach ($fields as $key => $value) {
            $field = Field::find($value['field_id']);
            $res = $field->update([
                'switch' => 2,
            ]);
            if(!$res){
                //事务回滚
                DB::rollBack();
                return response()->json([
                    'errcode' => 2,
                    'errmsg' => '购买失败',
                ],200);
            }

            $total += $value['price'];

        }

        // 生成订单
        $order_id = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);//随机的 13位订单号
        $order = Order::create([
            'id' => $order_id,
            'store_id' => $store_id,
            'status_id' => 4,//订单状态为  已完成
            'type_id' => $type_id,
            // 'payment_id' => $payment_id,
            'date' => $date, //买的 是 哪天的 商品
            'item_id' =>$item_id,
            'client_id' => $client_id,
            // 'phone' => $phone,
            'total' => $total,
            'collection' => $request->collection,
            'balance' => $total - $request->collection,
        ]);
  

        if(!$order){
            //回滚事务
            DB::rollBack();
            return response()->json([
                'errcode' => '2',
                'errmsg' => '购买失败',

            ],200);
        }

                  //生成订单状态的 数据
        $order_status = OrderStatus::create([
            'order_id' => $order->id,
            'status_id' => 4,
            'store_id' => $store_id,
        ]);

        if(!$order_status){
            //回滚事务
            DB::rollBack();
            return response()->json([
                'errcode' => '2',
                'errmsg' => '购买失败',

            ],200);

        }

        foreach ($fields as $key => $field) {

             //order_field 表 添加数据
            $field_order = Field_order::create([
                'order_id' => $order->id,
                'field_id' => $field['field_id'],
                'place_id' => $field['place_id'],
                'place_num' => $field['place_num'],
                'time' => $field['time'],
                'order_date' => $date,

            ]);

            if(!$field_order){
                //回滚事务
                DB::rollBack();
                return response()->json([
                    'errcode' => '2',
                    'errmsg' => '购买失败',

                ],200);

            }
    }
  
     // 提交事务
    DB::commit();

    return response()->json([
        'errcode' => 1,
        'order' => $order,
    ],200);
 }


   







    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // 订单详情
    public function show(Order $order)
    {
        // dump($order);
        $fields = $order->fields()->get();//订单买的 商品
            foreach ($fields as $key => $field) {
                $date = $field->pivot->order_date;//预定的场地日期
                $time = $field->pivot->time;//预定的场地时间段
                $place_num = $field->pivot->place_num;
                $field['time'] = $time;
                $field['place_num'] = $place_num;
                $field['date'] = $date;
            }
            // dump($fields);
            return response()->json([
                'errcode' => 1,
                'fields' => $fields,
            ],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
