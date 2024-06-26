<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\ProductSizes;
use App\Models\Sizes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use SebastianBergmann\Type\FalseType;

class CartController extends Controller
{
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
        $totalItems = 0;
        foreach ($cart as $item) {
            $totalItems += 1;
        }

        return response()->json(['success' => true, 'totalItems' => $totalItems]);
    }

    public function showCart()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $cartItems = Session::get("user_cart_$userId", []);

            foreach ($cartItems as $productIdAndSizeId => $item) {
                list($productId, $sizeId) = explode('_', $productIdAndSizeId);

                $product = Products::find($productId);
                $size = Sizes::find($sizeId);
                $productSize = ProductSizes::where('product_id', $productId)->where('size_id', $sizeId)->value('unit_price');
                $cartItems[$productIdAndSizeId]['product'] = $product;
                $cartItems[$productIdAndSizeId]['size'] = $size;
                $cartItems[$productIdAndSizeId]['productSize'] = $productSize;
                $cartItems[$productIdAndSizeId]['quantity'] = $item['quantity'];
            }
        } else {
            $cartItems = Session::get('guest_cart', []);

            foreach ($cartItems as $productIdAndSizeId => $item) {
                list($productId, $sizeId) = explode('_', $productIdAndSizeId);
                $product = Products::find($productId);
                $size = Sizes::find($sizeId);
                $productSize = ProductSizes::where('product_id', $productId)->where('size_id', $sizeId)->value('unit_price');;
                $cartItems[$productIdAndSizeId]['product'] = $product;
                $cartItems[$productIdAndSizeId]['size'] = $size;
                $cartItems[$productIdAndSizeId]['productSize'] = $productSize;
                $cartItems[$productIdAndSizeId]['quantity'] = $item['quantity'];
            }
        }
        return view('Cart', ['cartItems' => $cartItems]);
    }

    public function checkAuthCart()
    {
        if (Auth::check()) {
            $user_id = Auth::id();
            $user_cart = "user_cart_$user_id";
            return response()->json(['user_cart' => $user_cart, 'status' => 200]);
        } else {
            $user_cart = "guest_cart";
            return response()->json(['user_cart' => $user_cart, 'status' => 200]);
        }
    }

    public function removeFromCart($productIdAndSizeId)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $cartItems = Session::get("user_cart_$userId", []);

            if (array_key_exists($productIdAndSizeId, $cartItems)) {
                unset($cartItems[$productIdAndSizeId]);
                Session::put("user_cart_$userId", $cartItems);

                return response()->json(['success' => true, 'message' => 'Product removed from cart successfully', 'status' => 200]);
            } else {
                return response()->json(['success' => false, 'status' => 404]);
            }
        } else {
            $cartItems = Session::get('guest_cart', []);
            if (array_key_exists($productIdAndSizeId, $cartItems)) {
                unset($cartItems[$productIdAndSizeId]);
                Session::put("guest_cart", $cartItems);

                return response()->json(['success' => true, 'message' => 'Product removed from cart successfully', 'status' => 200]);
            } else {
                return response()->json(['success' => false, 'status' => 404]);
            }
        }
    }

    public function changeQuantity($productIdAndSizeId, $newQuantity)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $cartItems = Session::get("user_cart_$userId", []);

            if (array_key_exists($productIdAndSizeId, $cartItems)) {
                $cartItems[$productIdAndSizeId]['quantity'] = $newQuantity;
                Session::put("user_cart_$userId", $cartItems);

                return response()->json(['success' => true, 'message' => 'Change quantity from cart successfully', 'status' => 200]);
            }
        } else {
            $cartItems = Session::get('guest_cart', []);
            if (array_key_exists($productIdAndSizeId, $cartItems)) {
                $cartItems[$productIdAndSizeId]['quantity'] = $newQuantity;
                Session::put("guest_cart", $cartItems);

                return response()->json(['success' => true, 'message' => 'Change quantity from cart successfully', 'status' => 200]);
            }
        }
    }

    public function checkout(Request $request)
    {
        if (Auth::check()) {
            $user_id = Auth::id();
            $user_cart = "user_cart_$user_id";
        } else {
            $user_cart = "guest_cart";
        }
        $cartItems = $request->session()->get($user_cart);
        $productInfo = [];
        $outOfStockProducts = '';
        foreach ($cartItems as $key => $item) {
            [$productId, $sizeId] = explode('_', $key);
            $product = Products::select('product_name', 'status_in_stock', 'status')
                ->find($productId);
            if (!$product || $product->status == 0) {
                return response()->json(['message' => 'Product does not exist.', 'success' => false]);
            }

            $productInfo[] = [
                'product_name' => $product->product_name,
                'status_in_stock' => $product->status_in_stock,
            ];
            if ($product->status_in_stock == 0) {
                $outOfStockProducts .= ($outOfStockProducts == '') ? $product->product_name : ', ' . $product->product_name;
            }
        }

        if ($outOfStockProducts != '') {
            return response()->json(['message' => 'The following products are out of stock: ' . $outOfStockProducts . '. Please select another product or delete the product before continuing', 'success' => false]);
        } else {
            return response()->json(['message' => '', 'success' => true]);
        }
    }
}
