<?php

namespace App\Http\Guards;

use EcomDemo\Users\Services\TokensManager;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class JWTAuthGuard implements Guard
{
    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var UserProvider|null
     */
    protected ?UserProvider $provider;

    /**
     * @var Authenticatable|null
     */
    protected ?Authenticatable $user = null;

    /**
     * @param UserProvider|null $provider
     * @param Request $request
     */
    public function __construct(?UserProvider $provider, Request $request)
    {
        $this->provider = $provider;
        $this->request  = $request;
    }

    /**
     * @inheritDoc
     */
    public function check(): bool
    {
        return !is_null($this->user());
    }

    /**
     * @inheritDoc
     */
    public function guest(): bool
    {
        return !$this->check();
    }

    /**
     * @inheritDoc
     */
    public function user(): ?Authenticatable
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        /** @var TokensManager $tokensManager */
        $tokensManager = app(TokensManager::class);

        $jwtToken = $this->request->bearerToken();

        if (!$jwtToken || !$tokensManager->validate($jwtToken)) {
            return null;
        }

        try {
            // TODO: Refactor this so that JWT guard is independent from user, rather it can be any \Illuminate\Contracts\Auth\Authenticatable
            $this->user = $tokensManager->getUserFrom($jwtToken);
        } catch (\Exception $e) {
            return null;
        }

        return $this->user;
    }

    /**
     * @inheritDoc
     */
    public function id()
    {
        if ($this->user()) {
            return $this->user()->getAuthIdentifier();
        }

        return null;
    }

    /**
     * @param array<string, string> $credentials
     *
     * @return bool
     */
    public function validate(array $credentials = []): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function setUser(Authenticatable $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function hasUser(): bool
    {
        return !is_null($this->user);
    }
}
