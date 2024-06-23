<?php

namespace EcomDemo\Users\Repositories;

use Carbon\Carbon;
use EcomDemo\Users\Entities\JWTToken;
use EcomDemo\Users\Repositories\Contracts\JWTTokensRepository;

class EloquentJWTTokensRepository implements JWTTokensRepository
{
    /**
     * @inheritDoc
     */
    public function store(string $token, int $userId, string $tokenName, ?Carbon $expiresAt = null): JWTToken
    {
        $token = new JWTToken([
            'user_id'     => $userId,
            'unique_id'   => $token,
            'token_title' => $tokenName,
            'expires_at'  => $expiresAt
        ]);

        $token->store();

        return $token->refresh();
    }

    /**
     * @inheritDoc
     */
    public function touch(string $token): JWTToken
    {
        /** @var JWTToken $token */
        $token = JWTToken::where('unique_ID', $token)->first();

        $token->touch('last_used_at');

        return $token->refresh();
    }
}
