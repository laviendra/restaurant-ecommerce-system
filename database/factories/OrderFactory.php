<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'order_number' => 'ORD-' . $this->faker->unique()->numberBetween(100000, 999999),
            'order_status' => $this->faker->randomElement(['pending', 'confirmed', 'processing', 'completed', 'cancelled']),
            'payment_status' => $this->faker->randomElement(['pending', 'paid', 'failed']),
            'payment_method' => $this->faker->randomElement(['cod', 'transfer_bank']),
            'total_amount' => $this->faker->numberBetween(50000, 500000),
            'delivery_address' => $this->faker->address,
            'notes' => $this->faker->optional()->sentence,
            'payment_proof' => $this->faker->optional()->imageUrl(),
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }
}