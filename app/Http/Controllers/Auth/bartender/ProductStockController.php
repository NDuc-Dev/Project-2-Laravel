<?php

namespace App\Http\Controllers\auth\bartender;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;

class ProductStockController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkRole:bartender,bartender');
    }


    public function getProductStock()
    {
        return view('auth.bartender.productstock');
    }

    public function getDataProduct()
    {
        $data = Products::all();
        return response()->json($data);
    }
}
