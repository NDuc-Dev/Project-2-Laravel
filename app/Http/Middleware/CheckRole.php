<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (Auth::check()) {
            // Lấy vai trò của người dùng hiện tại
            $userRole = Auth::user()->role;

            // Kiểm tra xem vai trò của người dùng có trong danh sách các vai trò được phép hay không
            if (in_array($userRole, $roles)) {
                // Người dùng có quyền truy cập, tiếp tục xử lý request
                return $next($request);
            } else {
                // Người dùng không có quyền truy cập vào route này, bạn có thể chuyển hướng hoặc trả về một response lỗi
                return redirect()->back()->with('error', 'Bạn không có quyền truy cập vào trang này.');
            }
        }

        return redirect('/login');
    }
}
