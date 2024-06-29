<?php

namespace Tests\Feature\Users;

use EcomDemo\Files\Entities\File;
use EcomDemo\Users\Entities\User;
use Illuminate\Testing\TestResponse;
use Tests\Feature\AuthorizedTest;
use Tests\Feature\HasCustomerMiddleware;

class DeleteTest extends AuthorizedTest
{
    use HasCustomerMiddleware;

    public function test_can_delete_current_user()
    {
        /** @var User $user */
        $user = User::factory()->customer()->create();

        $this->makeAuthorizedRequest($user)
            ->assertOk()
            ->assertJsonStructure(['message']);

        $this->assertDatabaseMissing(User::class, ['id' => $user->getKey()]);
        $this->assertDatabaseMissing(File::class, ['uuid' => $user->getAvatarUuid()]);
    }

    /**
     * @inheritDoc
     */
    protected function makeAuthorizedRequest(?User $user = null): TestResponse
    {
        return $this->authorizedDelete('/api/v1/user', $user);
    }
}
