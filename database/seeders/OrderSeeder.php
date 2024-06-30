<?php

namespace Database\Seeders;

use EcomDemo\Orders\Entities\Order;
use EcomDemo\Orders\Entities\OrderStatus;
use EcomDemo\Users\Entities\User;
use EcomDemo\Users\Repositories\Contracts\UserRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class OrderSeeder extends Seeder
{
    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = $this->userRepository->getCustomers(10);

        /** @var Collection<string, OrderStatus> $statuses */
        $statuses = OrderStatus::inRandomOrder()->get();

        $customers->each(function ($customer) use ($statuses) {
            /** @var OrderStatus $status */
            $status = $statuses->random();

            /** @var User $customer */
            Order::factory()->create([
                'user_id'         => $customer->getKey(),
                'order_status_id' => $status->getkey(),
            ]);
        });

        foreach (range(1, 50) as $index) {
            /** @var OrderStatus $status */
            $status = $statuses->random();

            /** @var User $customer */
            $customer = $customers->random();

            Order::factory()->create([
                'user_id'         => $customer->getKey(),
                'order_status_id' => $status->getKey(),
            ]);
        }
    }
}
