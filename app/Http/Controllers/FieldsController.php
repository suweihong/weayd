<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Store;
use App\Models\Type;
use App\Models\Place;
use App\Models\StoreType;
use App\Models\Field;

class FieldsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type_id = $request->type_id;
        // $item_id = $request->item_id ?? 1;
        $store_id = $request->store_id;

         
         //现在的日期
        $now = date('Y-m-d',time());
        //要查询的日期
        $date = $request->date ?? $now;
          //日期存到session
        $time=1*51840000;
        setcookie(session_name(),session_id(),time()+$time,"/");
        $_SESSION['date']=$date;

        $date_time = strtotime($date);
        //要查询的那天是 周几
        $week = date('N',$date_time);

         //该店铺 该运动品类 的营业时间
        $store_hours = StoreType::where('store_id',$store_id)
                            ->where('type_id',$type_id)
                            ->where('item_id','1')
                            ->first();

          //运动品类营业的  开始时间
        if($store_hours){
            $hours = $store_hours->hours;
            $store_hours = explode('-', $hours);
            $start_time = (int)substr($store_hours[0],0,strrpos($store_hours[0],':')); 
        }else{
            $start_time = 0;
        }
        //读取所有价格
          $new_prices = Field::where('item_id',1)->where('store_id',$store_id)->where('type_id',$type_id)->where('date',$date)->orderBy('place_id','asc')->get();

        if($new_prices->isEmpty()){

            $new_prices = Field::where('item_id',1)->where('store_id',$store_id)->where('type_id',$type_id)->where('week',$week)->orderBy('place_id','asc')->get();

        }else{
             $price_week = Field::where('item_id',1)->where('store_id',$store_id)->where('type_id',$type_id)->where('week',$week)->orderBy('place_id','asc')->get();
             //将 星期价格 替换为 日期的价格
            foreach ($price_week as $key => $value) {
               foreach ($new_prices as $k => $v) {
                  if($value->place_id == $v->place_id && $value->time == $v->time){

                    //日期 没有设置开关时 显示星期的开关
                    if($v->switch == 3){
                      $v->switch = $value->switch;
                    }

                    $price_week[$key] = $v;
                  }
               }
            }
            $new_prices = $price_week;
        }
        $prices = $new_prices->groupBy('time')->sortBy('time');

        return response()->json([
            'prices' => $prices,
            'start_time' => $start_time,
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
