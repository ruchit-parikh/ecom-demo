<?php

namespace Tests\Feature\Auth;

use EcomDemo\Files\Entities\File;
use EcomDemo\Users\Entities\JWTToken;
use EcomDemo\Users\Entities\User;
use EcomDemo\Users\Repositories\Contracts\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_new_user()
    {
        $file = File::factory()->create();

        $response = $this->postJson('/api/v1/user/create', [
            'first_name'            => 'John',
            'last_name'             => 'Doe',
            'email'                 => 'johndoe@example.com',
            'password'              => 'admin@123',
            'password_confirmation' => 'admin@123',
            'address'               => 'Halol, Gujarat, India',
            'phone_number'          => '+919988776655',
            'is_marketing'          => '0',
            'avatar'                => $file->getUuid(),
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message','access_token', 'refresh_token']);

        $this->assertDatabaseHas(User::class, [
            'first_name'   => 'John',
            'last_name'    => 'Doe',
            'email'        => 'johndoe@example.com',
            'address'      => 'Halol, Gujarat, India',
            'phone_number' => '+919988776655',
            'is_marketing' => '0',
            'is_admin'     => false,
            'avatar'       => $file->getUuid(),
        ]);

        /** @var UserRepository $userRepository */
        $userRepository = app(UserRepository::class);

        /** @var User $user */
        $user = $userRepository->findByEmail('johndoe@example.com');

        $this->assertDatabaseHas(JWTToken::class, [
            'user_id'     => $user->getKey(),
            'token_title' => 'access_token',
        ]);

        $this->assertDatabaseHas(JWTToken::class, [
            'user_id'     => $user->getKey(),
            'token_title' => 'refresh_token',
        ]);
    }
}
