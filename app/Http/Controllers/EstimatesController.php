<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Estimate;

class EstimatesController extends Controller
{
		//	添加评论
    public function estimates(Request $request)
    {
    	$client_id = $request->client_id;
    	$content = $request->content;
    	$store_id = $request->store_id;
    	$order_id = $request->order_id;
        $type_id = $request->type_id;
    	$environment = $request->environment;
    	$service = $request->service;
    	$average = ($environment + $service) / 2;

    	$res = Estimate::create([
    		'client_id' => $client_id,
    		'content' => $content,
    		'store_id' => $store_id,
    		'order_id' => $order_id,
            'type_id' => $type_id,
    		'environment' => $environment,
    		'service' => $service,
    		'average' => $average,
    		'check_id' => 3,
    	]);
    	dump($res);
    	return response()->json([
    		'errcode' => 1,
    		'errmsg' => '评论成功',
    	],200);


    }

    //评论列表
    public function list(Request $request)
    {
    	
    	$store_id = $request->store_id;

    	$estimates = Estimate::where('store_id',$store_id)->where('check_id',6)->orderBy('created_at','desc')->get();

    	dump($estimates);
    }
}