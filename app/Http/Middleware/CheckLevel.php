<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckLevel
{
    public function handle(Request $request, Closure $next, ...$levels)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'ok' => false,
                'message' => 'กรุณาเข้าสู่ระบบ'
            ], 401);
        }

        if (!in_array($user->level, $levels)) {
            return redirect()->back()->with('error', 'คุณไม่มีสิทธิ์เข้าถึงข้อมูลนี้');
        }

        return $next($request);
    }
}