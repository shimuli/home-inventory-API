<?php

namespace Database\Seeders;

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
                // $categories = Categories::all()->random(random_int(1, 20))->pluck('id');
                // $product->categories()->attach($categories);
                $product->save();
            }
        );

    }
}
