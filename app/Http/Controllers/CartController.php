<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('Cart');
    }

    public function addToCart(Request $request, $productId)
    {

        if (!$request->session()->has('cart')) {
            $request->session()->put('cart', []);
        }

        $cart = $request->session()->get('cart');

        $cart[$productId] = [
            'quantity' => 1, // Số lượng sản phẩm
        ];

        $request->session()->put('cart', $cart);
    }
}
