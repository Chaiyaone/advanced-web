<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Order_Detail;
<<<<<<< HEAD
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
=======
use Illuminate\Http\Request;
>>>>>>> feature_order
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
<<<<<<< HEAD

    public function finish_order(Request $request)
    {
        $cart_items = Session::get('cart_items');
        if (!$cart_items) {
            return redirect('/cart/view')->with(['msg' => 'ไม่มีสินค้าในตะกร้า', 'ok' => false]);
        }

        $cust_name = $request->input('cust_name', 'ไม่ระบุชื่อ');
        $cust_email = $request->input('cust_email', '');

        // สร้างเลข order number
        $today = now()->format('Ymd');
        $countToday = Order::whereDate('created_at', today())->count();
        $sequence = $countToday + 1;
        $orderNumber = 'PO' . $today . $sequence;

        DB::beginTransaction();
        try {


            $order = Order::create([
                'order_number'  => $orderNumber,
                'customer_name' => $cust_name,
                'email'         => $cust_email,
                'status'        => 'ยังไม่ชำระเงิน',
                'order_date'    => now(),
            ]);

            foreach ($cart_items as $item) {
                Order_Detail::create([
                    'order_id' => $order->id,
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['qty'],
                    'total' => $item['price'] * $item['qty'],
                ]);

                // ลดจำนวนสินค้าใน products
                $product = \App\Models\Product::find($item['id']);
                if ($product) {
                    $product->stock_qty = max(0, $product->stock_qty - $item['qty']);
                    $product->save();
                }
            }

            DB::commit();
            Session::forget('cart_items');
            return redirect('/home')->with(['msg' => 'บันทึกคำสั่งซื้อสำเร็จ เลขที่สั่งซื้อ: ' . $orderNumber, 'ok' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/cart/view')->with(['msg' => 'เกิดข้อผิดพลาด: ' . $e->getMessage(), 'ok' => false]);
        }
    }

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
        return redirect('/orders')->with(['msg' => 'อัปเดตสถานะสำเร็จ', 'ok' => true]);
    }
}
=======
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
>>>>>>> feature_order
