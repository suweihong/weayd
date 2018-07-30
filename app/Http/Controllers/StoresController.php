<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Type;
use App\Models\Store;
use App\Models\Estimate;
use App\Models\Field;
use App\Models\StoreType;
class StoresController extends Controller
{
   	
   	//每个运动品类关联的商家 列表
   	public function type_stores(Request $request)
   	{
   		$type_id = $request->type_id;
   		$type_id = 2;
   		$type = Type::find($type_id);
   		$stores = $type->stores()->where('switch',1)->orderBy('created_at','asc')->get()->unique();
   		dump($stores);

   	}


   	//商家详情
   	public function store_show(Request $request)
   	{
   		$store_id = $request->store_id;
   		$store = Store::find($store_id);

   			//	该店的 所有 运动品类
   		$types = $store->types()->orderBy('created_at','asc')->get()->unique();

         $types_list = [];
   		foreach ($types as $key => $type) {
   			$type_name = $type->name; //运动品类名称
   			$type_id = $type->id;
   			$estimate = Estimate::where('store_id',$store_id)->where('type_id',$type_id)->where('check_id',6)->orderBy('created_at','desc')->first()->content ?? ''; //运动评价
 
   			$price_min = $store->fields()->where('type_id',$type_id)->pluck('price')->min();//该品类的 最低价格

               //该品类的 商品 票卡
            $item_id = StoreType::where('store_id',$store_id)->where('type_id',$type_id)->pluck('item_id');

          
            if($item_id[0] == 2){
               $fields = $store->fields()->where('type_id',$type_id)->where('item_id',2)->get();
               dump($fields);
            }else{
               $fields = [];
               dump(555);
            }

            $types_list[$key]['type_name'] = $type_name;
            $types_list[$key]['estimate'] = $estimate;
            $types_list[$key]['price_min'] = $price_min;
            $types_list[$key]['item_id'] = $item_id;
            $types_list[$key]['fields'] = $fields;
   		}
         dump($types_list);
   		

   			//场馆简介
   		$introduction = $store->introduction;

   			//	场馆实拍
   		$store_imgs = $store->imgs()->get();

         return response()->json([
            'errcode' => 1,
            'types_list' => $types_list,
            'introduction' => $introduction,
            'store_imgs' => $store_imgs,
         ],200);

   	}
}
