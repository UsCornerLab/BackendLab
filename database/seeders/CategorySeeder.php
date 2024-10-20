<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            "Fantasy",
            "Science Fiction",
            "Mystery",
            "Thriller",
            "Romance",
            "Historical Fiction",
            "Horror",
            "Crime Fiction",
            "Adventure",
            "Young Adult",
            "Philosophy",
            "Psychology",
            "Children's",
            "Biography",
            "Autobiography",
            "Memoir",
            "History",
            "Science",
            "Technology",
            "Business",
            "Self-Help",
            "Health",
            "Cookbooks",
            "Travel"
        ];

        foreach($categories as $category) {
            DB::table("Category")->insert(['category_name' => $category, "created_at" => now(), "updated_at" => now()]);
        }

    }
}
