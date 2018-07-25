<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Type;
use App\Models\Store;
use App\Models\Estimate;
use App\Models\Field;
class StoresController extends Controller
{
   	
   	//每个运动品类关联的商家
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
   		$store_id = 1;
   		$store = Store::find($store_id);

   			//	该店的 所有 运动品类
   		$types = $store->types()->orderBy('created_at','asc')->get()->unique();
   		foreach ($types as $key => $type) {
   			$type_name = $type->name; //运动品类名称
   			$type_id = $type->id;
   			$estimate = Estimate::where('store_id',$store_id)->where('type_id',$type_id)->where('check_id',6)->orderBy('created_at','desc')->first()->content ?? ''; //运动评价
 
   			$price = $store->fields()->where('type_id',$type_id)->pluck('price')->min();//该品类的 最低价格


   			dump($type_id);
   			dump($type_name);
   			dump($estimate);	
   			dump($price);

   		}
   		

   			//场馆简介
   		$introduction = $store->introduction;
   		// dump($introduction);

   			//	场馆实拍
   		$store_imgs = $store->imgs()->get();
   		// dump($store_imgs);

   	}
}
