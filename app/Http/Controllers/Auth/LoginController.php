<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
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

    public function logout(Request $request)
    {
        Auth::logout(); 
        return redirect('/login'); 
    }

    public function login(Request $request): RedirectResponse
    {
        try {
            $this->validateLogin($request);

            $credentials = $request->only('user_name', 'password');
            if (Auth::attempt($credentials)) {
                $userId = Auth::id();
                session(["user_cart_$userId" => []]);
                return redirect()->intended('/home');
            }

            // Đăng nhập thất bại
            return redirect()->route('login')->with('error', 'Invalid email or password');
        } catch (ValidationException $e) {
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
