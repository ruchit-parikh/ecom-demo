<?php

namespace EcomDemo\Users\Repositories;

use EcomDemo\Users\Entities\User;
use EcomDemo\Users\Repositories\Contracts\UserRepository;
use Hash;

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
            'password'     => Hash::make($data['password']),
            'address'      => $data['address'],
            'phone_number' => $data['phone_number']
        ]);

        $user->save();

        return $user->refresh();
    }
}
