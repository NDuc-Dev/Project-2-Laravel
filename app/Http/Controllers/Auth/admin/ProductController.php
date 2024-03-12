<?php

namespace App\Http\Controllers\auth\admin;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\GroupCategory;
use App\Models\Products;
use App\Models\ProductSizes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkRole:admin,admin');
    }

    public function getProduct()
    {
        $dataProducts = Products::all();
        $dataGroupCategory = GroupCategory::all();
        $dataCategory = Categories::all();
        return view('auth.admin.products.productmanage', compact('dataProducts', 'dataGroupCategory', 'dataCategory'));
    }

    public function createProducts(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'product_name' => 'required|string|max:255',
                'descriptions' => 'nullable|string',
                'group_category' => 'required|exists:group_category,group_id',
                'priceS' => 'nullable|numeric',
                'priceM' => 'nullable|numeric',
                'priceL' => 'nullable|numeric',
                'priceU' => 'nullable|numeric',
                'categorySelect' => 'required|exists:categories,category_name',
                'image-input' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
            if ($request->hasFile('image-input')) {
                $imageName = str_replace(' ', '-', strtolower($request->input('product_name'))) . '-' . time() . '.{extension}';
                $extension = $request->file('image-input')->getClientOriginalExtension();
                $newImageName = str_replace('{extension}', $extension, $imageName);
                $imagePath = $request->file('image-input')->storeAs('public/Product_image', $newImageName);
                $imagePath = asset('storage/' . substr($imagePath, 7));
            }

            $product = new Products();
            $product->product_name = $request->input('product_name');
            $product->descriptions = $request->input('descriptions');
            $product->product_category = $request->input('categorySelect');
            $product->product_images = $imagePath;
            if ($request->input("group_category") == 1) {
                $product->unit = "Cup";
            } else {
                $product->unit = "Piece/Pack";
            }
            $product->status = 0;
            $product->status_in_stock = 1;
            $product->save();

            $sizes = [
                [
                    'size_id' => 1,
                    'unit_price' => $request->input('priceS'),
                ],
                [
                    'size_id' => 2,
                    'unit_price' => $request->input('priceM'),
                ],
                [
                    'size_id' => 3,
                    'unit_price' => $request->input('priceL'),
                ],
                [
                    'size_id' => 4,
                    'unit_price' => $request->input('priceU'),
                ],
            ];
            foreach ($sizes as $size) {
                if ($request->input('group_category') == 1) {
                    if ($size['size_id'] <= 3) {
                        if ($size['unit_price'] !== null) {
                            $productSize = new ProductSizes();
                            $productSize->product_id = $product->product_id;
                            $productSize->size_id = $size['size_id'];
                            $productSize->unit_price = $size['unit_price'];
                            $productSize->save();
                        }
                    }
                } else if ($request->input('group_category') == 2) {
                    if ($size['size_id'] == 4) {
                        if ($size['unit_price'] !== null) {
                            $productSize = new ProductSizes();
                            $productSize->product_id = $product->product_id;
                            $productSize->size_id = $size['size_id'];
                            $productSize->unit_price = $size['unit_price'];
                            $productSize->save();
                        }
                    }
                }
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Product created successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'An error occurred while creating product'], 500);
        }
    }

    public function changeStatus($id)
    {
        DB::beginTransaction();

        $product = Products::find($id);

        try {
            $product->status = ($product->status == 1) ? 0 : 1;
            $product->save();
            DB::commit();
            return response()->json(['success' => true, 'messages' => "Change Status success"]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to change');
        }
    }

    public function getUpdateProduct($id)
    {
        $product = Products::find($id);
        $productSize = ProductSizes::where('product_id', $id)->get();
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        } else {
        }
        return view('auth.admin.products.updateProduct', compact('product', 'productSize'));
    }

    public function putUpdateProduct(Request $request, $product_id)
    {
        // Start transaction
        DB::beginTransaction();
        try {
            $request->validate([
                'product_name' => 'required|string|max:255',
                'descriptions' => 'nullable|string',
                'priceS' => 'nullable|numeric',
                'priceM' => 'nullable|numeric',
                'priceL' => 'nullable|numeric',
                'priceU' => 'nullable|numeric',
                'image-input' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $product = Products::findOrFail($product_id);


            // Xử lý hình ảnh nếu có
            if ($request->hasFile('image-input')) {
                $imageName = str_replace(' ', '-', strtolower($request->input('product_name'))) . '-' . time() . '.' . $request->file('image-input')->getClientOriginalExtension();
                $imagePath = $request->file('image-input')->storeAs('public/Product_image', $imageName);
                $product->product_images = asset('storage/' . substr($imagePath, 7));
            }

            // Cập nhật thông tin sản phẩm
            $product->product_name = $request->input('product_name');
            $product->descriptions = $request->input('descriptions');
            $product->save();



            $product->productSizes()->delete();
            // Thêm các size mới nếu có
            $sizes = [
                ['size_id' => 1, 'unit_price' => $request->input('priceS')],
                ['size_id' => 2, 'unit_price' => $request->input('priceM')],
                ['size_id' => 3, 'unit_price' => $request->input('priceL')],
                ['size_id' => 4, 'unit_price' => $request->input('priceU')],
            ];

            // return response()->json(['sizes' => $sizes], 200);

            $hasNonNullUnitPrice1 = false;
            $hasNonNullUnitPrice2 = false;
            $hasNonNullUnitPrice3 = false;
            $hasNonNullUnitPrice4 = false;

            // Duyệt qua mảng $sizes
            foreach ($sizes as $size) {
                // Kiểm tra size_id và unit_price
                if ($size['size_id'] == 1 && $size['unit_price'] != null) {
                    $hasNonNullUnitPrice1 = true;
                } elseif ($size['size_id'] == 2 && $size['unit_price'] != null) {
                    $hasNonNullUnitPrice2 = true;
                } elseif ($size['size_id'] == 3 && $size['unit_price'] != null) {
                    $hasNonNullUnitPrice3 = true;
                } elseif ($size['size_id'] == 4 && $size['unit_price'] != null) {
                    $hasNonNullUnitPrice4 = true;
                }
            }
            $isValidCase = false;
            $ngu = 0;
            foreach ($sizes as $size) {
                if ($hasNonNullUnitPrice1 && $hasNonNullUnitPrice2 && $hasNonNullUnitPrice3 && !$hasNonNullUnitPrice4) {

                    if ($size['size_id'] <= 3) {
                        $productSize = $product->productSizes()->create([
                            'product_id' => $product->product_id,
                            'size_id' => $size['size_id'],
                            'unit_price' => $size['unit_price']
                        ]);
                        $productSize->save();
                    }
                } elseif (!$hasNonNullUnitPrice1 && !$hasNonNullUnitPrice2 && !$hasNonNullUnitPrice3 && $hasNonNullUnitPrice4) {
                    if ($size['size_id'] == 4) {
                        $productSize = $product->productSizes()->create([
                            'product_id' => $product->product_id,
                            'size_id' => $size['size_id'],
                            'unit_price' => $size['unit_price']
                        ]);
                        $productSize->save();
                    }
                }
                $isValidCase = true;
            }
            if (!$isValidCase) {
                // Nếu không có trường hợp nào thỏa mãn, không commit transaction
                DB::rollback();
                return response()->json(['success' => false, 'message' => 'Invalid input data'], 400);
            }


            DB::commit();
            return response()->json(['success' => true, 'message' => 'Product updated successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'trace' => $e->getTraceAsString()], 500);
        }
    }
}
