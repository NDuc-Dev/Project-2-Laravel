<?php

namespace App\Console\Commands;

use App\Models\Categories;
use Illuminate\Console\Command;

class SeedCategoriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'categories:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Success';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Seed categories
        $categories = [
            ['category_name' => 'Coffee', 'descriptions' => 'description for Coffee category.','group_id' =>'1' ],
            ['category_name' => 'Tea', 'descriptions' => 'description for Tea category.','group_id' =>'1' ],
            ['category_name' => 'Cloud', 'descriptions' => 'description for Cloud category.','group_id' =>'1' ],
            ['category_name' => 'Healthy', 'descriptions' => 'description for Healthy category.','group_id' =>'1' ],
            ['category_name' => 'Green Tea', 'descriptions' => 'description for Green Tea category.','group_id' =>'1' ],
            ['category_name' => 'Chocolate', 'descriptions' => 'description for Chocolate category.','group_id' =>'1' ],
            ['category_name' => 'Frosty', 'descriptions' => 'description for Frosty category.','group_id' =>'1' ],
            ['category_name' => 'Savory cake', 'descriptions' => 'description for Savory cake category.','group_id' =>'2' ],
            ['category_name' => 'Sweet cake', 'descriptions' => 'description for Sweet cake category.','group_id' =>'2' ],
            ['category_name' => 'Snack', 'descriptions' => 'description for Snack category.','group_id' =>'2' ],
            ['category_name' => 'Pastry Cake', 'descriptions' => 'description for Pastry Cake category.','group_id' =>'2' ],
        ];

        foreach ($categories as $categoryData) {
            $category = Categories::firstOrCreate($categoryData);
            $this->info('Category ' . $category->category_name . ' seeded successfully.');
        }

        $this->info('Categories seeding completed.');
    }
}
