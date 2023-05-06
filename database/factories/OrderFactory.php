<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
    public function definition()
    {
        return [
            'creator_id' =>rand(1,3),
            'user_id' => rand(1,3),
            'discount_id' => rand(1,10),
            'discount_amount' =>"500000", // password
            'total_price' => (string)rand(500000,5000000),
            'payment_gate'=>fake()->title,
            'is_paid'=>fake()->boolean
        ];
    }
}
