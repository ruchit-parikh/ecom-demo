<?php

namespace Database\Seeders;

use EcomDemo\Orders\Entities\Order;
use EcomDemo\Users\Entities\User;
use EcomDemo\Users\Repositories\Contracts\UserRepository;
use Illuminate\Database\Seeder;

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

        $customers->each(function ($customer) {
            /** @var User $customer */
            Order::factory()->create(['user_id' => $customer->getKey()]);
        });

        foreach (range(1, 50) as $index) {
            /** @var User $customer */
            $customer = $customers->random();

            Order::factory()->create([
                'user_id' => $customer->getKey(),
            ]);
        }
    }
}
