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
        $this->tokensRepository->store($user, $accessToken);

        $refreshToken = $this->tokensService->getFreshTokenFor($user->getUuid(), 'refresh_token');
        $this->tokensRepository->store($user, $refreshToken);

        return ['access_token' => $accessToken->getToken(), 'refresh_token' => $refreshToken->getToken()];
    }

    /**
     * @param string $jwtToken
     *
     * @return bool
     */
    public function validate(string $jwtToken): bool
    {
        $token = $this->tokensService->parse($jwtToken, 'access_token');

        if (!$token) {
            return false;
        }

        $tokenInDb = $this->tokensRepository->findByUuid($token);

        if (!$tokenInDb || $tokenInDb->isExpired()) {
            return false;
        }

        return true;
    }

    /**
     * @param string $jwtToken
     *
     * @return User|null
     */
    public function getUserFrom(string $jwtToken): ?User
    {
        $token = $this->tokensService->parse($jwtToken, 'access_token');

        $this->tokensRepository->touch($token);

        /** @var UserRepository $userRepository */
        $userRepository = app(UserRepository::class);

        return $userRepository->findByUuid($token->getIssuedFor());
    }

    /**
     * @param string $jwtToken
     *
     * @return void
     */
    public function invalidate(string $jwtToken): void
    {
        $token = $this->tokensService->parse($jwtToken, 'access_token');

        if ($token) {
            $this->tokensRepository->expire($token);
        }
    }
}
