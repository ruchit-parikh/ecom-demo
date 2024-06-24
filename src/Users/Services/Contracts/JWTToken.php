<?php

namespace EcomDemo\Users\Services\Contracts;

use Carbon\Carbon;

abstract class JWTToken
{
    /**
     * @var string
     */
    protected string $token;

    /**
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    abstract public function getUuid(): string;

    /**
     * @return string|null
     */
    abstract public function getIssuedFor(): ?string;

    /**
     * @return string
     */
    abstract public function getType(): string;

    /**
     * @return Carbon|null
     */
    abstract public function getExpiresAt(): ?Carbon;

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }
}
