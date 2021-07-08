<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Products;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Products::factory()->times(600)->create()->each(
            function ($product) {
                $categories = Category::all()->random(random_int(1, 10))->pluck('id');
                $product->category()->attach($categories);
                $product->save();
            }
        );

    }
}
