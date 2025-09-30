<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthControllerApi extends Controller
{
    // Login API
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('name', $request->name)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'ok' => false,
                'message' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง'
            ], 401);
        }

        // สร้าง token พร้อมกำหนด abilities ตาม level
        $token = $user->createToken('api-token', [$user->level])->plainTextToken;

        return response()->json([
            'ok' => true,
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'level' => $user->level
            ]
        ]);
    }

    // Logout API
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'ok' => true,
            'message' => 'ออกจากระบบสำเร็จ'
        ]);
    }

    // Get User Info
    public function user(Request $request)
    {
        return response()->json([
            'ok' => true,
            'user' => $request->user()
        ]);
    }
}