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
            'price' => rand(99, 999),
            'description' => $this->faker->sentence(),
            'image' => $this->faker->image(),
            'category_id' => $category->id
        ];
    }
}
