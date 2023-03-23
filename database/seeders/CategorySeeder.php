<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productPrefixes = ['Sweater', 'Pants', 'Shirt', 'Hat', 'Glasses', 'Socks'];
        foreach ($productPrefixes as $name) {
            \App\Models\Category::create([
                'name' => $name,
                'slug' => Str::slug($name)
            ]);
        }
    }
}
