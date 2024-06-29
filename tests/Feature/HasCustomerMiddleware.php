<?php

namespace Tests\Feature;

use EcomDemo\Users\Entities\User;
use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\Response;

/**
 * @property AuthorizedTest $this
 */
trait HasCustomerMiddleware
{
    use HasAuthorization;

    public function test_customer_can_access()
    {
        /** @var User $user */
        $user = User::factory()->customer()->create();

        $response = $this->makeAuthorizedRequest($user);

        Assert::assertNotSame(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function test_admin_cannot_access()
    {
        /** @var User $user */
        $user = User::factory()->admin()->create();

        $response = $this->makeAuthorizedRequest($user);

        $response->assertForbidden();
    }
}
