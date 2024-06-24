<?php

namespace EcomDemo\Users\Repositories\Contracts;

use EcomDemo\Users\Entities\JWTToken;
use EcomDemo\Users\Entities\User;
use EcomDemo\Users\Services\Contracts\JWTToken as JWTTokenContract;

interface JWTTokensRepository
{
    /**
     * @param JWTTokenContract $token
     *
     * @return JWTToken|null
     */
    public function findByUUid(JWTTokenContract $token): ?JWTToken;

    /**
     * @param User $user
     * @param JWTTokenContract $token
     *
     * @return JWTToken
     */
    public function store(User $user, JWTTokenContract $token): JWTToken;

    /**
     * @param JWTTokenContract $token
     *
     * @return JWTToken
     */
    public function touch(JWTTokenContract $token): JWTToken;

    /**
     * @param JWTTokenContract $token
     *
     * @return JWTToken
     */
    public function expire(JWTTokenContract $token): JWTToken;
}
