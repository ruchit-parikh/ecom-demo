<?php

namespace EcomDemo\Users\Repositories;

use EcomDemo\Users\Entities\User;
use EcomDemo\Users\Repositories\Contracts\UserRepository;

class EloquentUserRepository implements UserRepository
{
    /**
     * @inheritDoc
     */
    public function findByUuid(string $uuid): User
    {
        /** @var User $user */
        $user = User::where('uuid', $uuid)->first();

        return $user;
    }
}
