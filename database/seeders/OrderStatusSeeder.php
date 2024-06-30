<?php

namespace Database\Seeders;

use EcomDemo\Orders\Entities\OrderStatus;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = OrderStatus::getPredefined();

        foreach ($statuses as $status) {
            OrderStatus::factory()->create(['slug' => $status]);
        }
    }
}
