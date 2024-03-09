<?php

namespace App\Http\Controllers\auth\seller;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Products;
use App\Models\ProductSizes;
use Illuminate\Http\Request;

class OrderManageController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkRole:seller,seller');
    }


    public function getOrderManage()
    {
        $drinkCategories = Categories::where('group_id', 1)->pluck('category_name');
        $foodcategories = Categories::where('group_id', 2)->pluck('category_name');
        $products = Products::all();
        return view('auth.seller.ordermanage', compact('products', 'drinkCategories', 'foodcategories'));
    }

    public function getDataProductSize(Request $request)
    {
        $product_id = $request->input('product_id');

        $productSize = ProductSizes::where('product_id', $product_id)->get();

        if ($productSize) {
            return response()->json(['success' => true, 'productSize' => $productSize]);
        } else {
            return response()->json(['success' => false, 'message' => 'Product size not found']);
        }
    }
}
