<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    protected $fillable = ['client_id','store_id','status_id','total','collection','balance','pay_id','phone','type_id','date','item_id','payment_id'];
}
