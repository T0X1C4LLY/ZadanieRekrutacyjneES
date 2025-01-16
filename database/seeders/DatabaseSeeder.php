<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::truncate();
        Tag::truncate();

        $quantityOfCategories = 10;
        $quantityOfTags = 10;

        for ($i = 0; $i < $quantityOfCategories; $i++) {
            Category::factory()->create();
        }

        for ($i = 0; $i < $quantityOfTags; $i++) {
            Tag::factory()->create();
        }
    }
}
