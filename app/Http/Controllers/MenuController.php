<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

            $product->unit_price = $price;
        }
        $foodProducts = Products::where('status', 1)->where('unit', 'Piece/Pack')->get();
        return view('menu', compact('drinkProducts', 'foodProducts'));
    }
}
