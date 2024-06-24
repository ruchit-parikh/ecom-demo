<?php

namespace EcomDemo\Users\Services\Contracts;

interface JWTTokensService
{
    /**
     * @param string $uuid
     * @param string $tokenName
     *
     * @return JWTToken
     */
    public function getFreshTokenFor(string $uuid, string $tokenName): JWTToken;

    /**
     * @param string $jwtToken
     * @param string $tokenName
     *
     * @return JWTToken|null
     */
    public function parse(string $jwtToken, string $tokenName): ?JWTToken;
}
