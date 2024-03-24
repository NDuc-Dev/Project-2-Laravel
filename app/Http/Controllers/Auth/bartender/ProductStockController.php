<?php

namespace App\Http\Controllers\auth\bartender;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductStockController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkRole:bartender,bartender');
    }

    
}
