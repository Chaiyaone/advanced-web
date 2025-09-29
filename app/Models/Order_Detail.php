<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_Detail extends Model
{
    use HasFactory;
    protected $table = 'order_details';
    public function order(){
        return $this->hasMany('App\Models\Order', 'order_id', 'id');
    }
}
