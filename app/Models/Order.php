<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    protected $fillable = ['client_id','store_id','status_id','total','collection','balance','pay_id','phone','type_id','date','item_id','payment_id'];

    //该订单的状态
    public function  status()
    {
    	return $this->belongsToMany('App\Models\Status')
                    ->wherePivot('deleted_at', null)
        			->withTimestamps();
    }

    //该订单最新状态
    public function new_status()
    {
    	return $this->status()
    				->orderBy('created_at','desc')
    				->first();
    }

    //获取该订单所属的商店
    public function store()
    {
    	return $this->belongsTo('App\Models\Store');
    }

    //获取该订单包括的商品
    public function fields()
    {
    	return $this->belongsToMany('App\Models\Field','field_order')->withPivot('place_num','time')->withTimestamps();
    }
    //获取该订单包括的 场地
    public function places()
    {
        return $this->belongsToMany('App\Models\Place', 'field_order', 'order_id', 'place_id')->withTimestamps();
    }
}
