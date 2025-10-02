<?php

namespace App\Models;



use App\Models\Order_Detail;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    
    protected $table = 'orders';
    
    protected $fillable = [
        'order_number',
        'customer_name', 
        'email',
        'order_date',
        'status'
    ];
    
    // ความสัมพันธ์ที่ถูกต้อง: Order hasMany OrderDetails
    public function orderDetails()
    {
        return $this->hasMany(Order_Detail::class, 'order_id', 'id');
    }
}