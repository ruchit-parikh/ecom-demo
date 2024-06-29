<?php

namespace Tests\Feature\Auth;

use EcomDemo\Users\Entities\JWTToken;
use EcomDemo\Users\Entities\User;
use EcomDemo\Users\Services\Contracts\JWTTokensService;
use EcomDemo\Users\Services\TokensManager;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    public function test_user_can_logout()
    {
        /** @var TokensManager $tokensManager */
        $tokensManager = app(TokensManager::class);

        /** @var User $user */
        $user   = User::factory()->customer()->create();
        $tokens = $tokensManager->generateForUser($user);

        $response = $this->postJson('/api/v1/user/logout', [], [
            'Authorization' => 'Bearer ' . $tokens['access_token']
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message']);

        /** @var JWTTokensService $tokensService */
        $tokensService = app(JWTTokensService::class);

        $parsed = $tokensService->parse($tokens['access_token'], 'access_token');

        $this->assertDatabaseMissing(JWTToken::class, [
            'user_id'     => $user->getKey(),
            'token_title' => $tokens['access_token'],
            'expires_at'  => $parsed->getExpiresAt()
        ]);
    }
}
