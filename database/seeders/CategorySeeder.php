<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Comedy',
            'Romance',
            'Horror',
            'Adventure',
            'Fiction',
            'Fantasy',
            'Drama',
            'Heartfelt',
            'Mystery',
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}
