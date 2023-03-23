<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CartsProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::all();
        //attach each cart with 1 - 5 amount of random product and random quantity
        Cart::all()->each(function ($cart) use ($products) {
            $cart->products()->attach(
                $products->random(rand(1, 5))->pluck('id'),
                ['quantity' => rand(1, 10)]
            );
        });
    }
}
