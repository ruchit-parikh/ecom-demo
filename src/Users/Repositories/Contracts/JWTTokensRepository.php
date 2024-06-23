<?php

namespace EcomDemo\Users\Repositories\Contracts;

use Carbon\Carbon;
use EcomDemo\Users\Entities\JWTToken;

interface JWTTokensRepository
{
    /**
     * @param string $token
     * @param int $userId
     * @param string $tokenName
     * @param Carbon|null $expiresAt
     *
     * @return JWTToken
     */
    public function store(string $token, int $userId, string $tokenName, ?Carbon $expiresAt = null): JWTToken;

    /**
     * @param string $token
     *
     * @return JWTToken
     */
    public function touch(string $token): JWTToken;
}
