<?php

namespace App\Http\Controllers\auth\admin;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Products;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
  

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

    public function createCategory(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'category_name' => 'required|string|max:255',
                'descriptions' => 'nullable|string',
                'categorySelect' => 'required|exists:group_category,group_id',
            ]);
            $category = new Categories();
            $category->category_name = $request->input('category_name');
            $category->descriptions = $request->input('descriptions');
            $category->group_id = $request->input('categorySelect');
            $category->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Category created successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'An error occurred while creating product'], 500);
        }
    }
}
