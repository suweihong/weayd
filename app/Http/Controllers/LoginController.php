<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Advertisement;
use App\Models\Type;
use App\Models\Store;

class LoginController extends Controller
{
    public function index(Request $request)
    {

    		//主广告位
    	$advertisement_main = Advertisement::where('type',1)->orderBy('created_at','desc')->get();
    		//次广告位   2x2 栏
    	$advertisement_second = Advertisement::where('type',2)->orderBy('created_at','desc')->get();
    		//次广告位  单栏
    	$advertisement_one = Advertisement::where('type',3)->orderBy('created_at','desc')->get();

    	
    		//所有的 运动品类
    	$types = Type::all();
    	
    	
    		//商家列表
    	$type_id = $request->type_id;
      
    	if($type_id){
    			//指定运动品类
    		$type = Type::find($type_id);
    		$stores = $type->stores()->orderBy('created_at','asc')->where('switch',1)->get()->unique();   		
    	}else{
    			//没 指定运动品类
    		$stores = Store::where('switch',1)->get()->take(5);
    		
    	}

    	$stores_list = [];
    	foreach ($stores as $key => $store) {
    			//	该商家的 运动品类
    		$types = $store->types()->get()->unique();
    		
    			//	该商家的 平均 评分
    		$average = $store->estimates()->get()->pluck('average')->avg();
    	

    			//免费体验标签
    		$price_min = $store->fields()->pluck('price')->min();
    	
    		$stores_list[$key]['store_title'] = $store->title;
    		$stores_list[$key]['types'] = $types;
    		$stores_list[$key]['average'] = $average;
    		$stores_list[$key]['price_min'] = $price_min;
    		
    	}
    	// dump($stores_list);

    	return response()->json([
    		'errcode' => 1,
    		'advertisement_main' => $advertisement_main,
    		'advertisement_one' => $advertisement_one,
    		'advertisement_second' => $advertisement_second,
    		'stores' => $stores_list,

    	],200);

    }
}
