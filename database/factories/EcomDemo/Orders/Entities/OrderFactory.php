<?php

namespace Database\Factories\EcomDemo\Orders\Entities;

use EcomDemo\Orders\Entities\Order;
use EcomDemo\Orders\Entities\OrderStatus;
use EcomDemo\Payments\Entities\Payment;
use EcomDemo\Users\Entities\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

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
     * @var array
     */
    protected $statuses = [];

    public function __construct(
        $count = null,
        ?Collection $states = null,
        ?Collection $has = null,
        ?Collection $for = null,
        ?Collection $afterMaking = null,
        ?Collection $afterCreating = null,
        $connection = null,
        ?Collection $recycle = null
    ) {
        $this->statuses = OrderStatus::all();

        parent::__construct($count, $states, $has, $for, $afterMaking, $afterCreating, $connection, $recycle);
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amount = fake()->randomFloat(4, 1, 3000);

        /** @var OrderStatus $status */
        $status = fake()->randomElement($this->statuses);

        $products = [];

        foreach (range(0, rand(1, 10)) as $count) {
            $products[] = ['product' => fake()->uuid(), 'quantity' => fake()->numberBetween(0, 10)];
        }

        return [
            'user_id'         => User::factory(),
            'order_status_id' => $status,
            'payment_id'      => $status->isShipped() || $status->isPaid() ? Payment::factory() : null,
            'products'        => $products,
            'address'         => ['billing' => fake()->address(), 'shipping' => fake()->address()],
            'delivery_fee'    => $amount > Order::FREE_DELIVERY_MIN_AMOUNT_ELIGIBLE ? 0 : Order::DELIVER_FEE,
            'amount'          => $amount,
            'shipped_at'      => $status->isShipped() ? fake()->dateTime() : null,
        ];
    }
}
