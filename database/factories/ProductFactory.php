<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $foodNames = [
            'Special Burger', 'Crispy Chicken', 'Deluxe Meal', 'Premium Sandwich',
            'Spicy Wings', 'Classic Fries', 'Cheese Nuggets', 'BBQ Burger',
            'Fish Fillet', 'Veggie Wrap', 'Chicken Wrap', 'Beef Wrap',
            'Double Cheese', 'Triple Stack', 'Mini Burger', 'Jumbo Meal'
        ];

        return [
            'category_id' => Category::factory(),
            'name' => fake()->randomElement($foodNames) . ' ' . fake()->word(),
            'description' => fake()->sentence(10),
            'price' => fake()->numberBetween(8000, 75000),
            'image' => null,
            'is_available' => fake()->boolean(90), // 90% available
            'is_featured' => fake()->boolean(20),  // 20% featured
        ];
    }

    /**
     * Indicate that the product is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    /**
     * Indicate that the product is unavailable.
     */
    public function unavailable(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_available' => false,
        ]);
    }
}