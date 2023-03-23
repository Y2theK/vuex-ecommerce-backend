<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $category = Category::inRandomOrder()->first();

        $productPrefixes = ['Sweater', 'Pants', 'Shirt', 'Hat', 'Glasses', 'Socks'];
        $name = $this->faker->company . ' ' . Arr::random($productPrefixes);
        return [
            'title' => $name,
            'slug' => Str::slug($name),
            'price' => $this->faker->randomFloat(8, 50, 100),
            'description' => $this->faker->realText(320),
            'image' => $this->faker->imageUrl($width=400, $height=400, ),
            'category_id' => $category->id,
            'rate' => $this->faker->randomFloat(8, 0, 5),
            'count' => rand(50, 100)
        ];
    }
}
