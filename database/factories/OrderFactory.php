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
            'total' => fake()->randomFloat(2, 50, 1000),
            'status' => fake()->randomElement(['pending', 'paid', 'shipped', 'delivered']),
            'shipping_address' => fake()->address(),
            'date' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
