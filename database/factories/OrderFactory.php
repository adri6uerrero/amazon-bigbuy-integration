<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'amazon_order_id' => $this->faker->uuid(),
            'status' => $this->faker->randomElement(['pendiente', 'enviado', 'error']),
            'tracking_number' => $this->faker->optional()->regexify('TRK[0-9]{8}'),
            'customer_id' => Customer::factory(),
        ];
    }
}
