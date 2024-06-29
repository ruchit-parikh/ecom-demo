<?php

namespace Tests\Feature\Users;

use EcomDemo\Files\Entities\File;
use EcomDemo\Users\Entities\User;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    public function test_can_delete_current_user()
    {
        /** @var User $user */
        $user = User::factory()->customer()->create();

        $this->authorizedDelete($user, '/api/v1/user')
            ->assertStatus(200)
            ->assertJsonStructure(['message']);

        $this->assertDatabaseMissing(User::class, ['id' => $user->getKey()]);
        $this->assertDatabaseMissing(File::class, ['uuid' => $user->getAvatarUuid()]);
    }
}
