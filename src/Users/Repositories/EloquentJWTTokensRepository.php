<?php

namespace EcomDemo\Users\Repositories;

use Carbon\Carbon;
use EcomDemo\Users\Entities\JWTToken;
use EcomDemo\Users\Entities\User;
use EcomDemo\Users\Repositories\Contracts\JWTTokensRepository;
use EcomDemo\Users\Services\Contracts\JWTToken as JWTTokenContract;

class EloquentJWTTokensRepository implements JWTTokensRepository
{
    /**
     * @inheritDoc
     */
    public function findByUUid(JWTTokenContract $token): ?JWTToken
    {
        /** @var JWTToken  $tokenInDb */
        $tokenInDb = JWTToken::where('unique_id', $token->getUuid())->first();

        return $tokenInDb;
    }

    /**
     * @inheritDoc
     */
    public function store(User $user, JWTTokenContract $token): JWTToken
    {
        $token = new JWTToken([
            'user_id'     => $user->getKey(),
            'unique_id'   => $token->getUuid(),
            'token_title' => $token->getType(),
            'expires_at'  => $token->getExpiresAt(),
        ]);

        $token->save();

        return $token->refresh();
    }

    /**
     * @inheritDoc
     */
    public function touch(JWTTokenContract $token): JWTToken
    {
        /** @var JWTToken $tokenInDb */
        $tokenInDb = JWTToken::where('unique_id', $token->getUuid())->firstOrFail();

        $tokenInDb->touch('last_used_at');

        return $tokenInDb->refresh();
    }

    /**
     * @inheritDoc
     */
    public function expire(JWTTokenContract $token): JWTToken
    {
        /** @var JWTToken $tokenInDb */
        $tokenInDb = JWTToken::where('unique_id', $token->getUuid())->firstOrFail();

        $tokenInDb->setExpiry(Carbon::now());

        $tokenInDb->save();

        return $tokenInDb->refresh();
    }
}
