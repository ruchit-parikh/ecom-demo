<?php

namespace Database\Factories\EcomDemo\Payments\Entities;

use EcomDemo\Payments\Entities\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type'    => fake()->slug(),
            'details' => fake()->randomElements(),
        ];
    }
}
