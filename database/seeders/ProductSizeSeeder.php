<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Danh sách sản phẩm và kích thước cùng với giá cả cho mỗi kích thước
        $productSizesData = [
            [
                'product_id' => 1,
                'size_id' => 1, 
                'unit_price' => 10000,
            ],
            [
                'product_id' => 1,
                'size_id' => 2, 
                'unit_price' => 15000,
            ],
            [
                'product_id' => 1,
                'size_id' => 3, 
                'unit_price' => 20000,
            ],
            [
                'product_id' => 2,
                'size_id' => 1, 
                'unit_price' => 10000,
            ],
            [
                'product_id' => 2,
                'size_id' => 2, 
                'unit_price' => 15000,
            ],
            [
                'product_id' => 2,
                'size_id' => 3, 
                'unit_price' => 20000,
            ],
            [
                'product_id' => 3,
                'size_id' => 1, 
                'unit_price' => 10000,
            ],
            [
                'product_id' => 3,
                'size_id' => 2, 
                'unit_price' => 15000,
            ],
            [
                'product_id' => 3,
                'size_id' => 3, 
                'unit_price' => 20000,
            ],
            [
                'product_id' => 4,
                'size_id' => 1, 
                'unit_price' => 10000,
            ],
            [
                'product_id' => 4,
                'size_id' => 2, 
                'unit_price' => 15000,
            ],
            [
                'product_id' => 4,
                'size_id' => 3, 
                'unit_price' => 20000,
            ],
            [
                'product_id' => 5,
                'size_id' => 1, 
                'unit_price' => 10000,
            ],
            [
                'product_id' => 5,
                'size_id' => 2, 
                'unit_price' => 15000,
            ],
            [
                'product_id' => 5,
                'size_id' => 3, 
                'unit_price' => 20000,
            ],
            [
                'product_id' => 6,
                'size_id' => 1, 
                'unit_price' => 10000,
            ],
            [
                'product_id' => 6,
                'size_id' => 2, 
                'unit_price' => 15000,
            ],
            [
                'product_id' => 6,
                'size_id' => 3, 
                'unit_price' => 20000,
            ],
            [
                'product_id' => 7,
                'size_id' => 1, 
                'unit_price' => 10000,
            ],
            [
                'product_id' => 7,
                'size_id' => 2, 
                'unit_price' => 15000,
            ],
            [
                'product_id' => 7,
                'size_id' => 3, 
                'unit_price' => 20000,
            ],
            [
                'product_id' => 8,
                'size_id' => 1, 
                'unit_price' => 10000,
            ],
            [
                'product_id' => 8,
                'size_id' => 2, 
                'unit_price' => 15000,
            ],
            [
                'product_id' => 8,
                'size_id' => 3, 
                'unit_price' => 20000,
            ],
            [
                'product_id' => 9,
                'size_id' => 1, 
                'unit_price' => 10000,
            ],
            [
                'product_id' => 9,
                'size_id' => 2, 
                'unit_price' => 15000,
            ],
            [
                'product_id' => 9,
                'size_id' => 3, 
                'unit_price' => 20000,
            ],
            [
                'product_id' => 10,
                'size_id' => 1, 
                'unit_price' => 10000,
            ],
            [
                'product_id' => 10,
                'size_id' => 2, 
                'unit_price' => 15000,
            ],
            [
                'product_id' => 10,
                'size_id' => 3, 
                'unit_price' => 20000,
            ],
            [
                'product_id' => 11,
                'size_id' => 1, 
                'unit_price' => 10000,
            ],
            [
                'product_id' => 11,
                'size_id' => 2, 
                'unit_price' => 15000,
            ],
            [
                'product_id' => 11,
                'size_id' => 3, 
                'unit_price' => 20000,
            ],
            [
                'product_id' => 12,
                'size_id' => 1, 
                'unit_price' => 10000,
            ],
            [
                'product_id' => 12,
                'size_id' => 2, 
                'unit_price' => 15000,
            ],
            [
                'product_id' => 12,
                'size_id' => 3, 
                'unit_price' => 20000,
            ],
            [
                'product_id' => 13,
                'size_id' => 1, 
                'unit_price' => 10000,
            ],
            [
                'product_id' => 13,
                'size_id' => 2, 
                'unit_price' => 15000,
            ],
            [
                'product_id' => 13,
                'size_id' => 3, 
                'unit_price' => 20000,
            ],
            [
                'product_id' => 14,
                'size_id' => 1, 
                'unit_price' => 10000,
            ],
            [
                'product_id' => 14,
                'size_id' => 2, 
                'unit_price' => 15000,
            ],
            [
                'product_id' => 14,
                'size_id' => 3, 
                'unit_price' => 20000,
            ],
            [
                'product_id' => 15,
                'size_id' => 1, 
                'unit_price' => 10000,
            ],
            [
                'product_id' => 15,
                'size_id' => 2, 
                'unit_price' => 15000,
            ],
            [
                'product_id' => 15,
                'size_id' => 3, 
                'unit_price' => 20000,
            ],
            [
                'product_id' => 16,
                'size_id' => 1, 
                'unit_price' => 10000,
            ],
            [
                'product_id' => 16,
                'size_id' => 2, 
                'unit_price' => 15000,
            ],
            [
                'product_id' => 16,
                'size_id' => 3, 
                'unit_price' => 20000,
            ],
            [
                'product_id' => 17,
                'size_id' => 1, 
                'unit_price' => 10000,
            ],
            [
                'product_id' => 17,
                'size_id' => 2, 
                'unit_price' => 15000,
            ],
            [
                'product_id' => 17,
                'size_id' => 3, 
                'unit_price' => 20000,
            ],
            [
                'product_id' => 18,
                'size_id' => 1, 
                'unit_price' => 10000,
            ],
            [
                'product_id' => 18,
                'size_id' => 2, 
                'unit_price' => 15000,
            ],
            [
                'product_id' => 18,
                'size_id' => 3, 
                'unit_price' => 20000,
            ],
            [
                'product_id' => 19,
                'size_id' => 1, 
                'unit_price' => 10000,
            ],
            [
                'product_id' => 19,
                'size_id' => 2, 
                'unit_price' => 15000,
            ],
            [
                'product_id' => 19,
                'size_id' => 3, 
                'unit_price' => 20000,
            ],
            [
                'product_id' => 20,
                'size_id' => 1, 
                'unit_price' => 10000,
            ],
            [
                'product_id' => 20,
                'size_id' => 2, 
                'unit_price' => 15000,
            ],
            [
                'product_id' => 20,
                'size_id' => 3, 
                'unit_price' => 20000,
            ],
            [
                'product_id' => 21,
                'size_id' => 1, 
                'unit_price' => 10000,
            ],
            [
                'product_id' => 21,
                'size_id' => 2, 
                'unit_price' => 15000,
            ],
            [
                'product_id' => 21,
                'size_id' => 3, 
                'unit_price' => 20000,
            ],
            [
                'product_id' => 22,
                'size_id' => 1, 
                'unit_price' => 10000,
            ],
            [
                'product_id' => 22,
                'size_id' => 2, 
                'unit_price' => 15000,
            ],
            [
                'product_id' => 22,
                'size_id' => 3, 
                'unit_price' => 20000,
            ],
            [
                'product_id' => 23,
                'size_id' => 1, 
                'unit_price' => 10000,
            ],
            [
                'product_id' => 23,
                'size_id' => 2, 
                'unit_price' => 15000,
            ],
            [
                'product_id' => 23,
                'size_id' => 3, 
                'unit_price' => 20000,
            ],
            [
                'product_id' => 24,
                'size_id' => 1, 
                'unit_price' => 10000,
            ],
            [
                'product_id' => 24,
                'size_id' => 2, 
                'unit_price' => 15000,
            ],
            [
                'product_id' => 24,
                'size_id' => 3, 
                'unit_price' => 20000,
            ],
            [
                'product_id' => 25,
                'size_id' => 1, 
                'unit_price' => 10000,
            ],
            [
                'product_id' => 25,
                'size_id' => 2, 
                'unit_price' => 15000,
            ],
            [
                'product_id' => 25,
                'size_id' => 3, 
                'unit_price' => 20000,
            ],
            // Thêm thông tin cho sản phẩm khác nếu cần
        ];

        // Insert dữ liệu vào bảng productsizes
        DB::table('productsizes')->insert($productSizesData);

        $this->command->info('Product Sizes batch seeding completed.');
    }
}
