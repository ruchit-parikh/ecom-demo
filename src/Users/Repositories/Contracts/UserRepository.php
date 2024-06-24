<?php

namespace EcomDemo\Users\Repositories\Contracts;

use EcomDemo\Users\Entities\User;

interface UserRepository
{
    /**
     * @param string $uuid
     *
     * @return User|null
     */
    public function findByUuid(string $uuid): ?User;

    /**
     * @param string $email
     *
     * @return User|null
     */
    public function findByEmail(string $email): ?User;

    /**
     * @param array $data
     *
     * @return User
     */
    public function create(array $data): User;
}
