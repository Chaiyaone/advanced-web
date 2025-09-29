<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders'; 
    public function orderDetails(){
        return $this->hasMany('App\Models\Order_Detail', 'order_id', 'id');
    }
}
