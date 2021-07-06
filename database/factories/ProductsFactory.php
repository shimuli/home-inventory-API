<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Products;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Products::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'brand' => $this->faker->word(),
            'quantity' => $this->faker->numberBetween(1, 30),
            'approx_price' => $this->faker->numberBetween(10, 5000),
            'current_price' => $this->faker->numberBetween(10, 5000),
            'status' => $this->faker->randomElement([Products::AVAILABLE_PRODUCT, Products::UNAVAILABLE_PRODUCT]),
            //'image' => $this->faker->randomElement(['1.png', '2.png', '3.png']),
            'category_id' => Category::all()->random()->id,
            'user_id' => User::all()->random()->id,
        ];

    }
}
