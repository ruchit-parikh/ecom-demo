<?php

namespace EcomDemo\Users\Repositories\Contracts;

use EcomDemo\Users\Entities\User;

interface UserRepository
{
    /**
     * @param string $uuid
     *
     * @return User
     */
    public function findByUuid(string $uuid): User;

    /**
     * @param array $data
     *
     * @return User
     */
    public function create(array $data): User;
}
