<?php

namespace Tests;

use App\Providers\TestServiceProvider;
use EcomDemo\Users\Entities\User;
use EcomDemo\Users\Services\TokensManager;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;
use Mail;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->register(TestServiceProvider::class);

        Mail::fake();
    }

    /**
     * @param User $user
     * @param string $uri
     * @param array $headers
     * @param int $options
     *
     * @return TestResponse
     */
    public function authorizedGet(User $user, string $uri, array $headers = [], int $options = 0): TestResponse
    {
        /** @var TokensManager $tokensManager */
        $tokensManager = app(TokensManager::class);

        $tokens = $tokensManager->generateForUser($user);

        return $this->getJson($uri, [
            'Authorization' => 'Bearer ' . $tokens['access_token'],
            ...$headers
        ], $options);
    }

    /**
     * @param User $user
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @param int $options
     *
     * @return TestResponse
     */
    public function authorizedPost(User $user, string $uri, array $data = [], array $headers = [], int $options = 0): TestResponse
    {
        /** @var TokensManager $tokensManager */
        $tokensManager = app(TokensManager::class);

        $tokens = $tokensManager->generateForUser($user);

        return $this->postJson($uri, $data, [
            'Authorization' => 'Bearer ' . $tokens['access_token'],
            ...$headers
        ], $options);
    }

    /**
     * @param User $user
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @param int $options
     *
     * @return TestResponse
     */
    public function authorizedPut(User $user, string $uri, array $data = [], array $headers = [], int $options = 0): TestResponse
    {
        /** @var TokensManager $tokensManager */
        $tokensManager = app(TokensManager::class);

        $tokens = $tokensManager->generateForUser($user);

        return $this->putJson($uri, $data, [
            'Authorization' => 'Bearer ' . $tokens['access_token'],
            ...$headers
        ], $options);
    }

    /**
     * @param User $user
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @param int $options
     *
     * @return TestResponse
     */
    public function authorizedDelete(User $user, string $uri, array $data = [], array $headers = [], int $options = 0): TestResponse
    {
        /** @var TokensManager $tokensManager */
        $tokensManager = app(TokensManager::class);

        $tokens = $tokensManager->generateForUser($user);

        return $this->deleteJson($uri, $data, [
            'Authorization' => 'Bearer ' . $tokens['access_token'],
            ...$headers
        ], $options);
    }
}
