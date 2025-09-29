<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$levels
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$levels)
    {
        // ตรวจสอบว่า user login หรือยัง
        if (!Auth::check()) {
            // ถ้าเป็น API request ให้ return 401
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            // ถ้าเป็น web request ให้ redirect ไป login
            return redirect()->route('login');
        }

        $userLevel = Auth::user()->level;

        // ตรวจสอบว่า user level ตรงกับที่อนุญาตหรือไม่
        if (!in_array($userLevel, $levels)) {
            // ถ้าเป็น API request
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized. Your role does not have permission.'], 403);
            }
            // ถ้าเป็น web request
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        return $next($request);
    }
}