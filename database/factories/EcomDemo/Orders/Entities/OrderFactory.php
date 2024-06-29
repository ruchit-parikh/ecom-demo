<?php

namespace Database\Factories\EcomDemo\Orders\Entities;

use EcomDemo\Orders\Entities\Order;
use EcomDemo\Orders\Entities\OrderStatus;
use EcomDemo\Payments\Entities\Payment;
use EcomDemo\Users\Entities\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'         => User::factory(),
            'order_status_id' => OrderStatus::factory(),
            'payment_id'      => Payment::factory(),
            'products'        => fake()->randomElements([['product' => fake()->uuid(), 'quantity' => fake()->numberBetween(0, 10)]]),
            'address'         => ['billing' => fake()->address(), 'shipping' => fake()->address],
            'delivery_fee'    => fake()->randomFloat(),
            'amount'          => fake()->randomFloat(),
            'shipped_at'      => fake()->dateTime(),
        ];
    }
}
