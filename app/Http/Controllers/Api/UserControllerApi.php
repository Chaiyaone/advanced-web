<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserControllerApi extends Controller
{
    /**
     * แสดงรายการ User ทั้งหมด
     * GET /api/users
     */
    public function index()
    {
        try {
            $users = User::all();
            
            return response()->json([
                'ok' => true,
                'users' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ค้นหา User
     * GET /api/users/search?q=keyword
     */
    public function search(Request $request)
    {
        try {
            $query = $request->input('q');
            
            if ($query) {
                $users = User::where('name', 'like', '%' . $query . '%')
                            ->orWhere('email', 'like', '%' . $query . '%')
                            ->get();
            } else {
                $users = User::all();
            }

            return response()->json([
                'ok' => true,
                'users' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * แสดงข้อมูล User ตาม ID
     * GET /api/users/{id}
     */
    public function show($id)
    {
        try {
            $user = User::find($id);
            
            if (!$user) {
                return response()->json([
                    'ok' => false,
                    'message' => 'ไม่พบข้อมูล User'
                ], 404);
            }

            return response()->json([
                'ok' => true,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * สร้าง User ใหม่
     * POST /api/users
     * Body: {
     *   "name": "John Doe",
     *   "email": "john@example.com",
     *   "password": "password123",
     *   "level": "customer"
     * }
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8',
                'level' => 'required|in:customer,admin,employee',
            ], [
                'required' => 'กรุณากรอกข้อมูล :attribute ให้ครบถ้วน',
                'unique' => 'อีเมลนี้ถูกใช้งานแล้ว',
                'email' => 'รูปแบบอีเมลไม่ถูกต้อง',
                'min' => 'รหัสผ่านต้องมีอย่างน้อย :min ตัวอักษร',
                'in' => 'ระดับผู้ใช้ไม่ถูกต้อง',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'level' => $validated['level'],
            ]);

            return response()->json([
                'ok' => true,
                'message' => 'สร้าง User สำเร็จ',
                'user' => $user
            ], 201);

        } catch (ValidationException $e) {
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
     * อัพเดทข้อมูล User
     * PUT /api/users/{id}
     * Body: {
     *   "name": "John Doe Updated",
     *   "email": "john.updated@example.com",
     *   "level": "employee"
     * }
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);
            
            if (!$user) {
                return response()->json([
                    'ok' => false,
                    'message' => 'ไม่พบข้อมูล User'
                ], 404);
            }

            $validated = $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'level' => 'required|in:customer,admin,employee',
                'password' => 'nullable|min:8', // password เป็น optional
            ], [
                'required' => 'กรุณากรอกข้อมูล :attribute ให้ครบถ้วน',
                'unique' => 'อีเมลนี้ถูกใช้งานแล้ว',
                'email' => 'รูปแบบอีเมลไม่ถูกต้อง',
                'min' => 'รหัสผ่านต้องมีอย่างน้อย :min ตัวอักษร',
                'in' => 'ระดับผู้ใช้ไม่ถูกต้อง',
            ]);

            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->level = $validated['level'];
            
            // ถ้ามีการส่ง password มาด้วย ให้อัพเดท
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            return response()->json([
                'ok' => true,
                'message' => 'อัพเดท User สำเร็จ',
                'user' => $user
            ]);

        } catch (ValidationException $e) {
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
     * ลบ User
     * DELETE /api/users/{id}
     */
    public function destroy($id)
    {
        try {
            $user = User::find($id);
            
            if (!$user) {
                return response()->json([
                    'ok' => false,
                    'message' => 'ไม่พบข้อมูล User'
                ], 404);
            }

            // ป้องกันไม่ให้ลบตัวเอง
            if (auth()->check() && auth()->id() == $id) {
                return response()->json([
                    'ok' => false,
                    'message' => 'ไม่สามารถลบบัญชีของตัวเองได้'
                ], 403);
            }

            $user->delete();

            return response()->json([
                'ok' => true,
                'message' => 'ลบ User สำเร็จ'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * เปลี่ยนรหัสผ่าน
     * POST /api/users/change-password
     * Body: {
     *   "current_password": "old_password",
     *   "new_password": "new_password",
     *   "new_password_confirmation": "new_password"
     * }
     */
    public function changePassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:8|confirmed',
            ], [
                'required' => 'กรุณากรอกข้อมูล :attribute ให้ครบถ้วน',
                'min' => 'รหัสผ่านใหม่ต้องมีอย่างน้อย :min ตัวอักษร',
                'confirmed' => 'รหัสผ่านใหม่ไม่ตรงกัน',
            ]);

            $user = $request->user();

            // ตรวจสอบรหัสผ่านเดิม
            if (!Hash::check($validated['current_password'], $user->password)) {
                return response()->json([
                    'ok' => false,
                    'message' => 'รหัสผ่านเดิมไม่ถูกต้อง'
                ], 422);
            }

            // อัพเดทรหัสผ่านใหม่
            $user->password = Hash::make($validated['new_password']);
            $user->save();

            return response()->json([
                'ok' => true,
                'message' => 'เปลี่ยนรหัสผ่านสำเร็จ'
            ]);

        } catch (ValidationException $e) {
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
}