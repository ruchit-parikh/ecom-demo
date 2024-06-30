<?php

namespace EcomDemo\Users\Repositories\Contracts;

use EcomDemo\Users\Entities\User;
use Illuminate\Support\Collection;

interface UserRepository
{
    /**
     * @param int $limit
     *
     * @return Collection<string, User>
     */
    public function getCustomers(int $limit): Collection;

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

    /**
     * @param User $user
     * @param array $data
     *
     * @return User
     */
    public function update(User $user, array $data): User;

    /**
     * @param User $user
     *
     * @return User
     */
    public function refreshLastLoggedIn(User $user): User;

    /**
     * @param User $user
     */
    public function delete(User $user): void;

    /**
     * @param string $email
     * @param string $password
     *
     * @return void
     */
    public function updatePassword(string $email, string $password): void;
}
