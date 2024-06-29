<?php

namespace Tests\Feature\Users;

use EcomDemo\Orders\Entities\Order;
use EcomDemo\Users\Entities\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\Feature\AuthorizedTest;
use Tests\Feature\HasCustomerMiddleware;

class OrdersTest extends AuthorizedTest
{
    use RefreshDatabase;
    use HasCustomerMiddleware;

    public function test_can_see_current_user_orders_first_page()
    {
        /** @var User $user */
        $user = User::factory()->customer()->create();

        /** @var Collection<string, Order> $orders */
        $orders = Order::factory(50)->create(['user_id' => $user->getKey()]);

        $resources = [];

        foreach ($orders->take(10) as $order) {
            /** @var Order $order */
            $payment = $order->getPayment();

            $resources[] = [
                'uuid'         => $order->getUuid(),
                'status'       => $order->getStatus(),
                'payment'      => [
                    'uuid'    => $payment->getUuid(),
                    'type'    => $payment->getType(),
                    'details' => $payment->getDetails(),
                ],
                'products'     => $order->getProducts(),
                'address'      => $order->getAddress(),
                'delivery_fee' => $order->getDeliveryFeeFormatted(),
                'amount'       => $order->getAmountFormatted(),
                'shipped_at'   => $order->shippedAt() ? $order->shippedAt()->toDayDateTimeString() : 'N/A',
                'placed_at'    => $order->getCreatedAt()->toDayDateTimeString(),
            ];
        }

        $this->makeAuthorizedRequest($user)
            ->assertJsonStructure(['data', 'links', 'meta'])
            ->assertJsonCount(10, 'data')
            ->assertJsonFragment(['data' => $resources]);
    }

    public function test_can_see_current_user_orders_given_number_page()
    {
        /** @var User $user */
        $user = User::factory()->customer()->create();

        /** @var Collection<string, Order> $orders */
        $orders = Order::factory(50)->create(['user_id' => $user->getKey()]);

        $resources = [];

        foreach ($orders->chunk(5)->offsetGet(2) as $order) {
            /** @var Order $order */
            $payment = $order->getPayment();

            $resources[] = [
                'uuid'         => $order->getUuid(),
                'status'       => $order->getStatus(),
                'payment'      => [
                    'uuid'    => $payment->getUuid(),
                    'type'    => $payment->getType(),
                    'details' => $payment->getDetails(),
                ],
                'products'     => $order->getProducts(),
                'address'      => $order->getAddress(),
                'delivery_fee' => $order->getDeliveryFeeFormatted(),
                'amount'       => $order->getAmountFormatted(),
                'shipped_at'   => $order->shippedAt() ? $order->shippedAt()->toDayDateTimeString() : 'N/A',
                'placed_at'    => $order->getCreatedAt()->toDayDateTimeString(),
            ];
        }

        $this->authorizedGet('/api/v1/user/orders?page=3&limit=5', $user)
            ->assertJsonStructure(['data', 'links', 'meta'])
            ->assertJsonCount(5, 'data')
            ->assertJsonFragment(['data' => $resources]);
    }

    /**
     * @inheritDoc
     */
    protected function makeAuthorizedRequest(?User $user = null): TestResponse
    {
        return $this->authorizedGet('/api/v1/user/orders', $user);
    }
}
