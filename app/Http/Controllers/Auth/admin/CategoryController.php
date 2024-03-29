<?php

namespace App\Http\Controllers\auth\admin;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Products;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkRole:admin,admin');
    }

    public function getCategories()
    {
        return view('auth.admin.Category');
    }

    public function getDataCategories()
    {
        $dataCategory = Categories::all();
        return response()->json(['dataCategory' => $dataCategory]);
    }

    public function getDataProduct($category)
    {
        $dataProducts = Products::where('product_category', $category)->get();
        return response()->json(['dataProducts' => $dataProducts, 'success' => true]);
    }
}
