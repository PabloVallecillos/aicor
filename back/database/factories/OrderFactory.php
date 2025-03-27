<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create()->id,
            'guest_id' => null,
            'total_amount' => $this->faker->randomFloat(2, 10, 1000),
            'status' => $this->faker->randomElement(Order::STATUSES),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
