<?php

namespace Database\Factories\EcomDemo\Orders\Entities;

use Carbon\Carbon;
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
        $amount = fake()->randomFloat(4, 1, 3000);

        $products = [];

        foreach (range(0, rand(1, 10)) as $count) {
            $products[] = ['product' => fake()->uuid(), 'quantity' => fake()->numberBetween(0, 10)];
        }

        return [
            'user_id'         => User::factory(),
            'order_status_id' => OrderStatus::factory(),
            'payment_id'      => null,
            'products'        => $products,
            'address'         => ['billing' => fake()->address(), 'shipping' => fake()->address()],
            'delivery_fee'    => $amount > Order::FREE_DELIVERY_MIN_AMOUNT_ELIGIBLE ? 0 : Order::DELIVER_FEE,
            'amount'          => $amount,
            'shipped_at'      => null,
        ];
    }

    /**
     * Define a state to handle shipped/paid orders based on provided or random status.
     *
     * @return Factory
     */
    public function configure()
    {
        return $this->afterMaking(function (Order $order) {
            $statusId = $order->getStatusId();

            /** @var OrderStatus $status */
            $status = OrderStatus::find($statusId);

            if ($status->isShipped() || $status->isPaid()) {
                $payment = Payment::factory()->create();

                $order->setPaymentId($payment->getKey());
            }

            if ($status->isShipped()) {
                $time = $this->faker->dateTime();

                $order->setShippedAt(Carbon::parse($time));
            }
        });
    }
}
