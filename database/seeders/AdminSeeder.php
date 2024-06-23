<?php

namespace Database\Seeders;

use EcomDemo\Users\Repositories\Contracts\UserRepository;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
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
        $this->userRepository->create([
            'first_name'   => 'Ecom',
            'last_name'    => 'Admin',
            'email'        => 'admin@buckhill.co.uk',
            'password'     => 'admin',
            'address'      => fake()->address(),
            'phone_number' => fake()->phoneNumber(),
        ]);
    }
}
