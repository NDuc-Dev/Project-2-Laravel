<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;

class MenuController extends Controller
{
    public function index()
    {
        $drinkProducts = Products::where('status', 1)
            ->where('unit', 'Cup')
            ->get();
        foreach ($drinkProducts as $product) {
            $price = DB::table('productsizes')
                ->where('product_id', $product->product_id)
                ->where('size_id', 1)
                ->value('unit_price');

            $unit_price = Number::currency($price, 'VND');
            $product->unit_price = $unit_price = preg_replace('/[^0-9,.]/', '', $unit_price);
        }

        $foodProducts = Products::where('status', 1)->where('unit', 'Piece/Pack')->get();
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
}
