<?php

namespace Tests\Feature;

use EcomDemo\Users\Entities\User;
use EcomDemo\Users\Services\TokensManager;
use Illuminate\Testing\TestResponse;

/**
 * @property AuthorizedTest $this
 */
trait HasAuthorization
{
    public function test_guest_cannot_access()
    {
        $this->makeAuthorizedRequest(null)
            ->assertUnauthorized()
            ->assertJsonStructure(['message']);
    }

    /**
     * @param string $uri
     * @param User|null $user
     * @param array $headers
     * @param int $options
     *
     * @return TestResponse
     */
    protected function authorizedGet(string $uri, ?User $user = null, array $headers = [], int $options = 0): TestResponse
    {
        $token = '';

        if ($user) {
            /** @var TokensManager $tokensManager */
            $tokensManager = app(TokensManager::class);

            $tokens = $tokensManager->generateForUser($user);
            $token  = $tokens['access_token'];
        }

        return $this->getJson($uri, [
            'Authorization' => 'Bearer ' . $token,
            ...$headers
        ], $options);
    }

    /**
     * @param string $uri
     * @param User|null $user
     * @param array $data
     * @param array $headers
     * @param int $options
     *
     * @return TestResponse
     */
    protected function authorizedPost(string $uri, ?User $user = null, array $data = [], array $headers = [], int $options = 0): TestResponse
    {
        $token = '';

        if ($user) {
            /** @var TokensManager $tokensManager */
            $tokensManager = app(TokensManager::class);

            $tokens = $tokensManager->generateForUser($user);
            $token  = $tokens['access_token'];
        }

        return $this->postJson($uri, $data, [
            'Authorization' => 'Bearer ' . $token,
            ...$headers
        ], $options);
    }

    /**
     * @param string $uri
     * @param User|null $user
     * @param array $data
     * @param array $headers
     * @param int $options
     *
     * @return TestResponse
     */
    protected function authorizedPut(string $uri, ?User $user = null, array $data = [], array $headers = [], int $options = 0): TestResponse
    {
        $token = '';

        if ($user) {
            /** @var TokensManager $tokensManager */
            $tokensManager = app(TokensManager::class);

            $tokens = $tokensManager->generateForUser($user);
            $token  = $tokens['access_token'];
        }

        return $this->putJson($uri, $data, [
            'Authorization' => 'Bearer ' . $token,
            ...$headers
        ], $options);
    }

    /**
     * @param string $uri
     * @param User|null $user
     * @param array $data
     * @param array $headers
     * @param int $options
     *
     * @return TestResponse
     */
    protected function authorizedDelete(string $uri, ?User $user = null, array $data = [], array $headers = [], int $options = 0): TestResponse
    {
        $token = '';

        if ($user) {
            /** @var TokensManager $tokensManager */
            $tokensManager = app(TokensManager::class);

            $tokens = $tokensManager->generateForUser($user);
            $token  = $tokens['access_token'];
        }

        return $this->deleteJson($uri, $data, [
            'Authorization' => 'Bearer ' . $token,
            ...$headers
        ], $options);
    }
}
