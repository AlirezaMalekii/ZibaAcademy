<?php

namespace Database\Factories;

use App\Models\Workshop;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $itemable=[
            Workshop::class
        ];
        return [
            'order_id'=>fake()->numberBetween(1,10),
            'itemable_type'=>fake()->randomElement($itemable),
            'itemable_id'=>rand(1,5),
            'price'=>"500000"
        ];
    }
}
