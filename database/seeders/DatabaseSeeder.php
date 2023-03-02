<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\CartsProductsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(5)->create();
        \App\Models\Category::factory(5)->create();
        \App\Models\Product::factory(30)->create();
        \App\Models\Cart::factory(10)->create();

        $this->call([
            CartsProductsSeeder::class
        ]);
    }
}
