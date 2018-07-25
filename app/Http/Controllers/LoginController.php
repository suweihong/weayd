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
    	$stores = Store::where('switch',1)->get()->take(5);
    	foreach ($stores as $key => $store) {
    			//	该商家的 运动品类
    		$type = $store->types()->get()->unique();
   
    			//	该商家的 平均 评分
    		$average = $store->estimates()->get()->pluck('average')->avg();
    		// dump($average);

    			//免费体验标签
    		$price_min = $store->fields()->pluck('price')->min();
    		if(!$price_min || $price_min == 0){
    			dump(88);
    		}else{
    			dump($price_min);
    		}
    		
 

    	}
    	
    	




    }
}
