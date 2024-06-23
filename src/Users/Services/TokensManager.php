<?php

namespace EcomDemo\Users\Services;

use EcomDemo\Users\Entities\User;
use EcomDemo\Users\Repositories\Contracts\JWTTokensRepository;
use EcomDemo\Users\Repositories\Contracts\UserRepository;
use EcomDemo\Users\Services\Contracts\JWTTokensService;

class TokensManager
{
    /**
     * @var JWTTokensService
     */
    protected JWTTokensService $tokensService;

    /**
     * @var JWTTokensRepository
     */
    protected JWTTokensRepository $tokensRepository;

    /**
     * @param JWTTokensService $tokensService
     * @param JWTTokensRepository $tokensRepository
     */
    public function __construct(JWTTokensService $tokensService, JWTTokensRepository $tokensRepository)
    {
        $this->tokensService    = $tokensService;
        $this->tokensRepository = $tokensRepository;
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function generateForUser(User $user): array
    {
        $accessToken = $this->tokensService->getFreshTokenFor($user->getUuid(), 'access_token');
        $expiry      = $this->tokensService->getExpiry($accessToken);

        $this->tokensRepository->store($accessToken, $user->getKey(), 'access_token', $expiry);

        $refreshToken = $this->tokensService->getFreshTokenFor($user->getUuid(), 'refresh_token');
        $expiry       = $this->tokensService->getExpiry($refreshToken);

        $this->tokensRepository->store($refreshToken, $user->getKey(), 'refresh_token', $expiry);

        return ['access_token' => $accessToken, 'refresh_token' => $refreshToken];
    }


    /**
     * @param string $jwtToken
     *
     * @return User
     */
    public function getUserFrom(string $jwtToken): User
    {
        $uuid = $this->tokensService->getClaimFrom($jwtToken);

        /** @var UserRepository $userRepository */
        $userRepository = app(UserRepository::class);

        return $userRepository->findByUuid($uuid);
    }
}
