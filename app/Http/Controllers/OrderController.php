<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Order_Detail;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // แสดงรายการทั้งหมด
    public function index()
    {
        $orders = Order::latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    // แสดงรายละเอียดของ Order
    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $orderItems = Order_Detail::where('order_id', $id)->get();
        return view('orders.edit', compact('order', 'orderItems'));
    }

    // อัปเดตสถานะการชำระเงิน
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->input('status');
        $order->save();
        return redirect()->route('orders.edit', $id);
    }
}
