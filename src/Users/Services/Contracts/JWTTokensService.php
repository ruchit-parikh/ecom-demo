<?php

namespace EcomDemo\Users\Services\Contracts;

use Carbon\Carbon;

interface JWTTokensService
{
    /**
     * @param string $uuid
     * @param string $tokenName
     *
     * @return string
     */
    public function getFreshTokenFor(string $uuid, string $tokenName): string;

    /**
     * @param string $jwtToken
     *
     * @return string
     */
    public function getClaimFrom(string $jwtToken): string;

    /**
     * @param string $jwtToken
     *
     * @return bool
     */
    public function validate(string $jwtToken): bool;

    /**
     * @param string $jwtToken
     *
     * @return Carbon|null
     */
    public function getExpiry(string $jwtToken): ?Carbon;
}
