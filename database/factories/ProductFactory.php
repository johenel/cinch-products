<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $productNames = [
            'Washing Machine',
            'Hammer',
            'Screw Driver',
            'Electric Fan',
            'Television',
            'Air Purifier',
            'Kettle',
            'Stove',
            'Glass',
            'Spoon',
            'Fork',
            'Plate',
            'Water Dispenser'
        ];

        $randomName = $productNames[rand(0, count($productNames) - 1)];

        return [
            'name' => $randomName . ' #' . now()->timestamp . '-' . rand(1,1000),
            'description' => implode(" ", fake()->sentences),
            'stock' => rand(1, 100),
            'price' => $this->faker->randomFloat(2, 10, 1000)
        ];
    }
}
