<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        return view('Cart');
    }

    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');
        $sizeId = $request->input('size_id');
        $key = $productId . '_' . $sizeId;
        if (Auth::check()) {
            $userId = Auth::id();
            if (!session()->has("user_cart_$userId")) {
                session(["user_cart_$userId" => []]);
            }
            $cart = $request->session()->get("user_cart_$userId");
            if (array_key_exists($key, $cart)) {
                $newQuantity = $cart[$key]['quantity'] + $quantity;
                $cart[$key]['quantity'] = min($newQuantity, 10);
            } else {
                $cart[$key] = [
                    'quantity' => min($quantity, 10),
                ];
            }
            $request->session()->put("user_cart_$userId", $cart);
        } else {
            if (!session()->has('guest_cart')) {
                session(['guest_cart' => []]);
            }
            $cart = $request->session()->get('guest_cart');
            if (array_key_exists($key, $cart)) {
                $newQuantity = $cart[$key]['quantity'] + $quantity;
                $cart[$key]['quantity'] = min($newQuantity, 10);
            } else {
                $cart[$key] = [
                    'quantity' => min($quantity, 10),
                ];
            }
            $request->session()->put('guest_cart', $cart);
        }

        return response()->json(['success' => true]);

    }
}
