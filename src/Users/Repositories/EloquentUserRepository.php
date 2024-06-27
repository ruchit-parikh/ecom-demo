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

        if (isset($data['is_admin'])) {
            $user->asAdmin(true);
        }

        $user->save();

        return $user->refresh();
    }

    /**
     * @inheritDoc
     */
    public function update(User $user, array $data): User
    {
        if (!empty($data['first_name'])) {
            $user->setFirstName($data['first_name']);
        }

        if (!empty($data['last_name'])) {
            $user->setLastName($data['last_name']);
        }

        if (!empty($data['email'])) {
            $user->setEmail($data['email']);
        }

        if (!empty($data['address'])) {
            $user->setAddress($data['address']);
        }

        if (!empty($data['phone_number'])) {
            $user->setPhoneNumber($data['phone_number']);
        }

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
    public function delete(User $user): void
    {
        $avatar = $user->getAvatar();

        $user->delete();

        if (!is_null($avatar)) {
            $avatar->delete();
        }
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
