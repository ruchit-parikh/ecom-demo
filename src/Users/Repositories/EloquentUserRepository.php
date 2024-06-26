<?php

namespace EcomDemo\Users\Repositories;

use EcomDemo\Users\Entities\User;
use EcomDemo\Users\Repositories\Contracts\UserRepository;

class EloquentUserRepository implements UserRepository
{
    /**
     * @inheritDoc
     */
    public function findByUuid(string $uuid): ?User
    {
        /** @var User $user */
        $user = User::where('uuid', $uuid)->first();

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function findByEmail(string $email): ?User
    {
        /** @var User $user */
        $user = User::where('email', $email)->first();

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function create(array $data): User
    {
        $user = new User([
            'first_name'   => $data['first_name'],
            'last_name'    => $data['last_name'],
            'email'        => $data['email'],
            'address'      => $data['address'],
            'phone_number' => $data['phone_number']
        ]);

        $user->setPassword($data['password']);

        if (isset($data['is_marketing'])) {
            $user->setMarketingPreference($data['is_marketing']);
        }

        if (!empty($data['avatar'])) {
            $user->setAvatarUuid($data['avatar']);
        }

        $user->save();

        return $user->refresh();
    }

    /**
     * @inheritDoc
     */
    public function refreshLastLoggedIn(User $user): User
    {
        $user->refreshLastLoggedIn();

        $user->save();

        return $user->refresh();
    }

    /**
     * @inheritDoc
     */
    public function updatePassword(string $email, string $password): void
    {
        /** @var User $user */
        $user = User::where('email', '=', $email)->firstOrFail();

        $user->setPassword($password)
            ->saveOrFail();
    }
}
