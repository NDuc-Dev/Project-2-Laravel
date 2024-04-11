<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('guest');
    // }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'user_name' => ['required', 'string', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:255'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\Users
     */
    protected function create(array $data)
    {
        try {
            $user = Users::create([
                'name' => $data['name'],
                'user_name' => $data['user_name'],
                'password' => Hash::make($data['password']),
                'token' => Str::random(20, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'),
                'role' => 'guest',
                'email' => $data['email'],
                'phone' => $data['phone'],
                'status' => 0,
            ]);

            if ($user) {
                Mail::send('emails.active_account', compact('user'), function ($email) use ($user) {
                    $email->subject('NDC COFFEE - Confirm registration and activate your account.');
                    $email->to($user->email, $user->name);
                });
            }

            return response()->json(['success' => true, 'message' => 'Success, please check your email for active account'], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function registerAjax(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        event(new Registered($user = $this->create($request->all())));

        return response()->json(['success' => true, 'message' => 'Success, we will send you an email to activate your account. Please check your email.'], 201);
    }

    public function checkExistInfo(Request $request)
    {
        $username = $request->input('username');
        $phone = $request->input('phone');
        $mail = $request->input('email');
        $user_name = Users::where('user_name', $username)->first();
        $phone_num = Users::where('phone', $phone)->first();
        $email = Users::where('email', $mail)->first();

        if ($user_name) {
            return response()->json(['exists' => true, 'message' => "User Name is already exist !"]);
        } else if ($phone_num) {
            return response()->json(['exists' => true, 'message' => "Phone Number is already exist !"]);
        } else if ($email) {
            return response()->json(['exists' => true, 'message' => "Email is already exist !"]);
        } else {
            return response()->json(['exists' => false]);
        }
    }

    public function activedAccount(Users $user, $token)
    {
        if ($user->token === $token) {
            $user->status = 1;
            $user->token = null;
            $user->save();
            return view('activated');
        } else {
            return view('errors.404');
        }
    }
}
