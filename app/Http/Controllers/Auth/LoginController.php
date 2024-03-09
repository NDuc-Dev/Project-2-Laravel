<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout()
    {
        Auth::logout(); // Đăng xuất người dùng

        // Thực hiện các thao tác khác nếu cần
        // Ví dụ: Xóa thông tin đăng nhập khác, xóa cookie, ...

        return redirect('/login'); // Chuyển hướng về trang chủ hoặc trang khác sau khi đăng xuất
    }

    public function login(Request $request): RedirectResponse
    {
        try {
            // Validate dữ liệu từ form đăng nhập
            $this->validateLogin($request);

            // Thực hiện đăng nhập
            $credentials = $request->only('user_name', 'password');
            if (Auth::attempt($credentials)) {
                // Đăng nhập thành công
                return redirect()->intended('/home');
            }

            // Đăng nhập thất bại
            return redirect()->route('login')->with('error', 'Invalid email or password');
        } catch (ValidationException $e) {
            // Nếu có lỗi validation, chuyển hướng với thông báo lỗi
            return redirect()->route('login')
                ->withErrors($e->validator)
                ->withInput();
        }
    }

    // Hàm để validate dữ liệu đăng nhập
    protected function validateLogin(Request $request): void
    {
        $request->validate([
            'user_name' => 'required',
            'password' => 'required',
        ]);
    }
}
