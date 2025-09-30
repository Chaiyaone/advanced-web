<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Order_Detail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderControllerApi extends Controller
{
    /**
     * แสดงรายการ Order ทั้งหมด
     * GET /api/orders
     */
    public function index()
    {
        try {
            $orders = Order::latest()->paginate(10);
            
            return response()->json([
                'ok' => true,
                'orders' => $orders->items(),
                'pagination' => [
                    'total' => $orders->total(),
                    'per_page' => $orders->perPage(),
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * แสดงรายละเอียดของ Order พร้อม Order Items
     * GET /api/orders/{id}
     */
    public function show($id)
    {
        try {
            $order = Order::find($id);
            
            if (!$order) {
                return response()->json([
                    'ok' => false,
                    'message' => 'ไม่พบข้อมูล Order'
                ], 404);
            }

            $orderItems = Order_Detail::where('order_id', $id)->get();

            return response()->json([
                'ok' => true,
                'order' => $order,
                'order_items' => $orderItems
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * สร้าง Order ใหม่
     * POST /api/orders
     * Body: {
     *   "user_id": 1,
     *   "total": 1500,
     *   "status": "pending",
     *   "items": [
     *     {"product_id": 1, "quantity": 2, "price": 500},
     *     {"product_id": 2, "quantity": 1, "price": 500}
     *   ]
     * }
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'total' => 'required|numeric|min:0',
                'status' => 'nullable|string',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.price' => 'required|numeric|min:0',
            ]);

            // สร้าง Order
            $order = Order::create([
                'user_id' => $validated['user_id'],
                'total' => $validated['total'],
                'status' => $validated['status'] ?? 'pending',
            ]);

            // สร้าง Order Details
            foreach ($validated['items'] as $item) {
                Order_Detail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            // โหลดข้อมูล order พร้อม items
            $order->load('orderDetails');

            return response()->json([
                'ok' => true,
                'message' => 'สร้าง Order สำเร็จ',
                'order' => $order
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'ok' => false,
                'message' => 'ข้อมูลไม่ถูกต้อง',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * อัพเดทสถานะ Order
     * PUT /api/orders/{id}
     * Body: {"status": "completed"}
     */
    public function update(Request $request, $id)
    {
        try {
            $order = Order::find($id);
            
            if (!$order) {
                return response()->json([
                    'ok' => false,
                    'message' => 'ไม่พบข้อมูล Order'
                ], 404);
            }

            $validated = $request->validate([
                'status' => 'required|string',
            ]);

            $order->status = $validated['status'];
            $order->save();

            return response()->json([
                'ok' => true,
                'message' => 'อัพเดท Order สำเร็จ',
                'order' => $order
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'ok' => false,
                'message' => 'ข้อมูลไม่ถูกต้อง',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ลบ Order
     * DELETE /api/orders/{id}
     */
    public function destroy($id)
    {
        try {
            $order = Order::find($id);
            
            if (!$order) {
                return response()->json([
                    'ok' => false,
                    'message' => 'ไม่พบข้อมูล Order'
                ], 404);
            }

            // ลบ Order Details ก่อน
            Order_Detail::where('order_id', $id)->delete();
            
            // ลบ Order
            $order->delete();

            return response()->json([
                'ok' => true,
                'message' => 'ลบ Order สำเร็จ'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ดึง Order ของ User ที่ Login
     * GET /api/orders/my-orders
     */
    public function myOrders(Request $request)
    {
        try {
            $user = $request->user();
            $orders = Order::where('user_id', $user->id)
                          ->latest()
                          ->paginate(10);
            
            return response()->json([
                'ok' => true,
                'orders' => $orders->items(),
                'pagination' => [
                    'total' => $orders->total(),
                    'per_page' => $orders->perPage(),
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }
}