<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Users;
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

    public function login(Request $request)
    {
        try {
            $this->validateLogin($request);
            $credentials = $request->only('user_name', 'password');

            // Thêm kiểm tra trạng thái của người dùng vào điều kiện đăng nhập
            $user = Users::where('user_name', $credentials['user_name'])->first();
            if (!$user) {
                return response()->json(['success' => false, 'message' => "Invalid User Name or Password, Please re-enter"]);
            } else if ($user->status == 0) {
                return response()->json(['success' => false, 'message' => "Your Account is not actived"]);
            } else if (Auth::attempt($credentials)) {
                $userId = Auth::id();
                session(["user_cart_$userId" => []]);
                return response()->json(['success' => true, 'message' => "Welcome $user->user_name"]);
            }
            return response()->json(['success' => false, 'message' => "Invalid User Name or Password, Please re-enter"]);
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
