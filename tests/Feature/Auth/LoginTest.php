<?php

namespace Tests\Feature\Auth;

use EcomDemo\Users\Entities\JWTToken;
use EcomDemo\Users\Entities\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_user_can_login_with_correct_credentials()
    {
        /** @var User $user */
        $user = User::factory()->customer()->create([
            'password' => Hash::make('admin@123'),
        ]);

        $response = $this->postJson('/api/v1/user/login', [
            'email'    => $user->getEmail(),
            'password' => 'admin@123'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['access_token', 'refresh_token']);

        $this->assertDatabaseHas(JWTToken::class, [
            'user_id'     => $user->getKey(),
            'token_title' => 'access_token',
        ]);

        $this->assertDatabaseHas(JWTToken::class, [
            'user_id'     => $user->getKey(),
            'token_title' => 'refresh_token',
        ]);
    }

    public function test_user_cannot_login_with_incorrect_credentials()
    {
        /** @var User $user */
        $user = User::factory()->customer()->create([
            'password' => Hash::make('admin@123'),
        ]);

        $response = $this->postJson('/api/v1/user/login', [
            'email'    => $user->getEmail(),
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure(['message']);

        $this->assertDatabaseMissing(JWTToken::class, [
            'user_id'     => $user->getKey(),
            'token_title' => 'access_token',
        ]);

        $this->assertDatabaseMissing(JWTToken::class, [
            'user_id'     => $user->getKey(),
            'token_title' => 'refresh_token',
        ]);
    }
}
