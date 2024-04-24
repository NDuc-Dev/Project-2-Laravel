<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\ProductSizes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;

class MenuController extends Controller
{
    public function index()
    {
        $drinkProducts = Products::where('status', 1)
            ->where('unit', 'Cup')
            ->where('status_in_stock', '1')
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
            ->where('status_in_stock', '1')
            ->get();
        foreach ($foodProducts as $product) {
            $price = DB::table('productsizes')
                ->where('product_id', $product->product_id)
                ->where('size_id', 4)
                ->value('unit_price');

            $unit_price = Number::currency($price, 'VND');
            $product->unit_price = $unit_price = preg_replace('/[^0-9,.]/', '', $unit_price);
        }
        return view('menu', compact('drinkProducts', 'foodProducts'));
    }

    public function getProductfood($id)
    {
        $product = Products::find($id);
        $product->unit_price = preg_replace('/[^0-9,.]/', '', Number::currency(ProductSizes::where('product_id', $id)->value('unit_price'), 'VND'));
        return response()->json(['product' => $product, 'status' => 200]);
    }

    public function getProductdrink($id)
    {
        $product = Products::find($id);
        $prices = ProductSizes::where('product_id', $id)->get();
        foreach ($prices as $price) {
            $price->unit_price =  preg_replace('/[^0-9,.]/', '', Number::currency($price->unit_price, 'VND'));
        }
        return response()->json(['product' => $product, 'prices' => $prices, 'status' => 200]);
    }
}
