<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Order_Detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * แสดงรายการ Order ทั้งหมด
     */
    public function index()
    {
        $orders = Order::orderBy('id', 'asc')->get();
        return view('order.index', compact('orders'));
    }

    /**
     * แสดงรายละเอียด Order
     */
    public function show($id)
    {
        $order = Order::with('orderDetails')->findOrFail($id);
        
        // คำนวณยอดรวม
        $total_amount = $order->orderDetails->sum('total');
        
        return view('order.show', compact('order', 'total_amount'));
    }

    /**
     * อัพเดทสถานะการชำระเงิน
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:ยังไม่ชำระเงิน,ชำระเงินแล้ว'
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->route('order.index')
            ->with('success', 'อัพเดทสถานะเรียบร้อยแล้ว');
    }

    /**
     * ค้นหา Order
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        $orders = Order::where('order_number', 'like', '%' . $query . '%')
            ->orWhere('customer_name', 'like', '%' . $query . '%')
            ->orWhere('email', 'like', '%' . $query . '%')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('order.index', compact('orders', 'query'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:ยังไม่ชำระเงิน,ชำระเงินแล้ว'
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->route('order.index')
            ->with('success', 'อัพเดทสถานะเรียบร้อยแล้ว');
    }
}