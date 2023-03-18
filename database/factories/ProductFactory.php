<?php

namespace Database\Factories;

use App\Models\Category;
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
        return [
            'title' => $this->faker->word(),
            'price' => $this->faker->randomFloat(8, 9, 99),
            'description' => $this->faker->sentence(),
            'image' => $this->faker->imageUrl($width=400, $height=400, ),
            'category_id' => $category->id,
            'rate' => $this->faker->randomFloat(8, 0, 5),
            'count' => rand(50, 100)
        ];
    }
}
