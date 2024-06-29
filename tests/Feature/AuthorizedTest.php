<?php

namespace Tests\Feature;

use EcomDemo\Users\Entities\User;
use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class AuthorizedTest extends TestCase
{
    use HasAuthorization;

    /**
     * @param User|null $user
     *
     * @return TestResponse
     */
    protected function makeAuthorizedRequest(?User $user = null): TestResponse
    {
        if (!$user) {
            return new TestResponse(new Response(['message' => 'Failed'], Response::HTTP_UNAUTHORIZED));
        }

        return new TestResponse(new Response());
    }
}
