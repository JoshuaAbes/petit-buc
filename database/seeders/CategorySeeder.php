<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/categories.json'));
        $data = json_decode($json, true);
        foreach ($data['categories'] as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
