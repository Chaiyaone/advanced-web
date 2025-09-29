<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Order_Detail;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function viewCart()
    {
        $cart_items = Session::get('cart_items');
        return view('cart/index', compact('cart_items'));
    }

    public function addToCart($id = null)
    {
        $product = Product::find($id);
        $cart_items = Session::get('cart_items');
        if (is_null($cart_items)) {
            $cart_items = array();
        }
        $qty = 0;
        if (array_key_exists($product->id, $cart_items)) {
            $qty = $cart_items[$product->id]['qty'];
        }
        $cart_items[$product['id']] = array(
            'id' => $product->id,
            'code' => $product->code,
            'name' => $product->name,
            'price' => $product->price,
            'image_url' => $product->image_url,
            'qty' => $qty + 1,
        );
        Session::put('cart_items', $cart_items);
        return redirect('cart/view');
    }

    public function deleteCart($id)
    {
        $cart_items = Session::get('cart_items');
        unset($cart_items[$id]);
        Session::put('cart_items', $cart_items);
        return redirect('cart/view');
    }
    public function updateCart($id = null, $qty = null)
    {
        $cart_items = Session::get('cart_items');
        $cart_items[$id]['qty'] = $qty;
        Session::put('cart_items', $cart_items);
        return redirect('cart/view');
    }
    public function checkout()
    {
        $cart_items = Session::get('cart_items');
        return view('cart/checkout', compact('cart_items'));
    }
    public function complete(Request $request)
    {
        $cart_items = Session::get('cart_items');
        $cust_name = $request->input('cust_name');
        $cust_email = $request->input('cust_email');
        $po_no = 'PO' . date("Ymd");
        $po_date = date("Y-m-d H:i:s");
        $total_amount = 0;
        foreach ($cart_items as $c) {
            $total_amount += $c['price'] * $c['qty'];
        }

        return view('cart/complete', compact(
            'cart_items',
            'cust_name',
            'cust_email',
            'po_no',
            'po_date',
            'total_amount'
        ));
    }
    public function finish_order(Request $request)
{
    // Validate input
    $request->validate([
        'cust_name' => 'required|string|max:255',
        'cust_email' => 'required|email'
    ]);
    
    $cart_items = Session::get('cart_items');
    
    if (!$cart_items || count($cart_items) == 0) {
        return redirect('/cart/view')->with('error', 'ไม่มีสินค้าในตะกร้า');
    }

    DB::beginTransaction();
    
    try {
        // สร้างเลขที่ใบสั่งซื้อแบบไม่ซ้ำ
        $order_number = 'PO' . date('YmdHis') . rand(100, 999);
        
        // สร้าง Order
        $order = Order::create([
            'order_number' => $order_number,
            'customer_name' => $request->input('cust_name'),
            'email' => $request->input('cust_email'),
            'order_date' => now(),
            'status' => 'ยังไม่ชำระเงิน',
        ]);

        // วนลูปบันทึก OrderDetail และลดสต็อก
        foreach ($cart_items as $item) {
            // หา product จาก ID
            $product = Product::find($item['id']);
            
            if (!$product) {
                throw new \Exception('ไม่พบสินค้า: ' . $item['name']);
            }
            
            // ตรวจสอบสต็อก
            if ($product->stock_qty < $item['qty']) {
                throw new \Exception(
                    'สินค้า ' . $product->name . 
                    ' มีไม่เพียงพอ (คงเหลือ: ' . $product->stock_qty . ')'
                );
            }
            
            // บันทึกรายละเอียดการสั่งซื้อ
            Order_Detail::create([
                'order_id' => $order->id,
                'product_name' => $product->name,
                'price' => $product->price,
                'quantity' => $item['qty'],
                'total' => $product->price * $item['qty'],
            ]);
            
            // ลดจำนวนสินค้าในสต็อก
            $product->decrement('stock_qty', $item['qty']);
        }

        DB::commit();
        
        // ล้าง Session
        Session::forget('cart_items');
        
        return redirect('/home')
            ->with('success', 'บันทึกใบสั่งซื้อเรียบร้อย เลขที่: ' . $order_number);
        
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect('/cart/view')
            ->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
    }
}
}