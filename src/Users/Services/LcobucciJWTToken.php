<?php

namespace EcomDemo\Users\Services;

use Carbon\Carbon;
use EcomDemo\Users\Services\Contracts\JWTToken;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Token\RegisteredClaims;

class LcobucciJWTToken extends JWTToken
{
    /**
     * @var Token
     */
    protected Token $lcobucciToken;

    /**
     * @param Token $token
     */
    public function __construct(Token $token)
    {
        $this->lcobucciToken = $token;

        parent::__construct($token->toString());
    }

    /**
     * @inheritDoc
     */
    public function getUuid(): string
    {
        return $this->lcobucciToken->claims()->get('uuid');
    }

    /**
     * @inheritDoc
     */
    public function getIssuedFor(): ?string
    {
        return $this->lcobucciToken->claims()->get('issued_for');
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return $this->lcobucciToken->claims()->get(RegisteredClaims::SUBJECT);
    }

    /**
     * @return Carbon|null
     */
    public function getExpiresAt(): ?Carbon
    {
        $expiresAt = $this->lcobucciToken->claims()->get(RegisteredClaims::EXPIRATION_TIME);

        return $expiresAt ? Carbon::parse($expiresAt) : null;
    }
}
