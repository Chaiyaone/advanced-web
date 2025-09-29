<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Order_Detail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

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
        $cart_items = Session::get('cart_items');
        if (!$cart_items) {
            return redirect('/cart/view')->with(['msg' => 'ไม่มีสินค้าในตะกร้า', 'ok' => false]);
        }

        // ข้อมูลลูกค้า
        $cust_name = $request->input('cust_name', 'ไม่ระบุชื่อ');
        $cust_email = $request->input('cust_email', '');

        // สร้างเลข order number
        $today = now()->format('Ymd');
        $countToday = Order::whereDate('created_at', today())->count();
        $sequence = $countToday + 1;
        $orderNumber = 'PO' . $today . $sequence;

        DB::beginTransaction();
        try {
            // 1) สร้าง Order
            $order = Order::create([
                'order_number'  => $orderNumber,
                'customer_name' => $cust_name,
                'email'         => $cust_email,
                'status'        => 'ยังไม่ชำระเงิน',
                'order_date'    => now(),
            ]);

            // 2) สร้าง Order_Detail จาก cart_items
            foreach ($cart_items as $item) {
                Order_Detail::create([
                    'order_id' => $order->id,
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['qty'],
                    'total' => $item['price'] * $item['qty'],
                ]);
            }

            DB::commit();
            Session::forget('cart_items');
            return redirect('/home')->with(['msg' => 'บันทึกคำสั่งซื้อสำเร็จ เลขที่สั่งซื้อ: ' . $orderNumber, 'ok' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/cart/view')->with(['msg' => 'เกิดข้อผิดพลาด: ' . $e->getMessage(), 'ok' => false]);
        }
    }
}