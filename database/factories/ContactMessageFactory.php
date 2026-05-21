<?php

namespace Database\Factories;

use App\Models\ContactMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactMessageFactory extends Factory
{
    protected $model = ContactMessage::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'subject' => $this->faker->sentence,
            'message' => $this->faker->paragraph,
            'is_read' => $this->faker->boolean(30), // 30% chance of being read
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }
}