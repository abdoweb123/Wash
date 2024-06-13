<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = new Category();
        $category->title_ar = 'بلايستيشن';
        $category->title_en = 'Playstation';
        $category->save();
        $category = new Category();
        $category->title_ar = 'ايتيونز';
        $category->title_en = 'Itunes';
        $category->save();
        $category = new Category();
        $category->title_ar = 'جوجل ماركت';
        $category->title_en = 'Google Play';
        $category->save();
        $category = new Category();
        $category->title_ar = 'ستيم';
        $category->title_en = 'Steam';
        $category->save();
        $category = new Category();
        $category->title_ar = 'خدمات الموبايل والانترنت';
        $category->title_en = 'Mobile and Internet';
        $category->save();
    }
}
