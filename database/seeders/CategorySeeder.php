<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $items = [
            ['name_en' => 'Men', 'name_ar' => 'رجال', 'slug' => 'men', 'status' => 'active'],
            ['name_en' => 'Women', 'name_ar' => 'نساء', 'slug' => 'women', 'status' => 'active'],
            ['name_en' => 'Kids', 'name_ar' => 'أطفال', 'slug' => 'kids', 'status' => 'active'],
        ];

        foreach ($items as $i => $row) {
            Category::firstOrCreate(['slug' => $row['slug']], array_merge($row, ['sort_order' => $i]));
        }
    }
}
