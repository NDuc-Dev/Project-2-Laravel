<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\ProductSizes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;

class ProductDetailsController extends Controller
{
    public function index($id)
    {
        $product = Products::find($id);
        $prices = ProductSizes::where('product_id', $id)->get();
        if ($prices->count() == 1) {
            $product->unit_price = preg_replace('/[^0-9,.]/', '', Number::currency(ProductSizes::where('product_id', $id)->value('unit_price'), 'VND'));
            return view('productDetailsFood', compact('product'));
        } else {
            foreach ($prices as $price) {
                $price->unit_price = preg_replace('/[^0-9,.]/', '', Number::currency($price->unit_price, 'VND'));
            }
            return view('productDetailsDrink', compact('product', 'prices'));
        }
    }
}
