<?php

namespace Database\Seeders;

use EcomDemo\Users\Entities\User;
use Hash;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(10)->customer()->create(['password' => Hash::make('userpassword')]);
    }
}
