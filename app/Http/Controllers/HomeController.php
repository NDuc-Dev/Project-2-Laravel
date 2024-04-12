<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $drinkProducts = Products::where('status', 1)
            ->where('unit', 'Cup')
            ->take(3)
            ->get();
        foreach ($drinkProducts as $product) {
            $price = DB::table('productsizes')
                ->where('product_id', $product->product_id)
                ->where('size_id', 1)
                ->value('unit_price');

            $unit_price = Number::currency($price, 'VND');
            $product->unit_price = $unit_price = preg_replace('/[^0-9,.]/', '', $unit_price);
        }
        $foodProducts = Products::where('status', 1)
            ->where('unit', 'Piece/Pack')
            ->take(3)
            ->get();
        foreach ($foodProducts as $product) {
            $price = DB::table('productsizes')
                ->where('product_id', $product->product_id)
                ->where('size_id', 4)
                ->value('unit_price');

            $unit_price = Number::currency($price, 'VND');
            $product->unit_price = $unit_price = preg_replace('/[^0-9,.]/', '', $unit_price);
        }
        return view('home', compact('drinkProducts', 'foodProducts'));
    }

    public function forgotPass()
    {
        return view('forgotpassword');
    }

    public function postforgotPass(Request $request)
    {
        $user = Users::where('email', $request->email)->first();
        if ($user) {
            $user->token = Str::random(20, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');
            $user->save();
            Mail::send('emails.forgot_password', compact('user'), function ($email) use ($user) {
                $email->subject('NDC COFFEE - Confirm password change.');
                $email->to($user->email, $user->name);
            });
            return response()->json(['success' => true, 'message' => 'Success, please check your email to reset password'], 200);
        } else {
            return response()->json(['success' => true, 'message' => 'Success, please check your email to reset password'], 200);
        }
    }

    public function getNewPassword(Users $user, $token)
    {
        if ($user->token === $token) {
            $u_id = $user->user_id;
            return view('getNewPassword', compact('u_id'));
        } else {
            return view('errors.404');
        }
    }

    public function postNewPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'confirmation_password' => 'required|same:password',
        ], [
            'confirmation_password.same' => 'The password confirmation does not match.',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $request->errors()->first()]);
        }
        $password_h = bcrypt($request->password);
        $user = Users::find($request->input('u_id'));
        $user->password = $password_h;
        $user->token = null;
        $user->save();
        return response()->json(['success' => true, 'message' => 'Change password success, you can login now.']);
    }


    // public function getActive()
    // {
    //     return view('getActive');
    // }

    // public function postActive(Request $request)
    // {
    //     $email = $request->input('email');
    //     $user = Users::where('email', $email)->first();
    //     if ($user) {
    //         if ($user->status == 1) {
    //             return response()->json(['success' => false, 'message' => 'Error, Your account has been activated, no need to activate again']);
    //         } else {
    //             $user->token =  Str::random(20, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');
    //             $user->save();
    //             Mail::send('emails.active_account', compact('user'), function ($email) use ($user) {
    //                 $email->subject('NDC COFFEE - Confirm registration and activate your account.');
    //                 $email->to($user->email, $user->name);
    //             });
    //             return response()->json(['success' => true, 'message' => 'Success, we will send you an email to activate your account. Please check your email.']);
    //         }
    //     } else {
    //         return response()->json(['success' => false, 'message' => 'Error, Account is not exits, please try again']);
    //     }
    // }
}
