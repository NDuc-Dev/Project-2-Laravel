<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Mail;
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
            Mail::send('emails.active_account', compact('user'), function ($email) use ($user) {
                $email->subject('NDC COFFEE - Confirm password change.');
                $email->to($user->email, $user->name);
            });
            return response()->json(['success' => true, 'message' => 'Success, please check your email to reset password'], 200);
        } else {
            return response()->json(['success' => true, 'message' => 'Success, please check your email to reset password'], 200);
        }
    }
}
