<?php

namespace Tests\Feature\Users;

use EcomDemo\Files\Entities\File;
use EcomDemo\Users\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_edit_current_user()
    {
        /** @var File $file */
        $file = File::factory()->create();

        /** @var User $user */
        $user = User::factory()->customer()->create([
            'first_name'   => 'Naruto',
            'last_name'    => 'Uzumakki',
            'email'        => 'narutouzumaki@hiddenleaf.com',
            'password'     => 'admin@123',
            'address'      => 'Hidden Leaf',
            'phone_number' => '+919988776655',
            'is_marketing' => '0',
            'avatar'       => $file->getUuid(),
        ]);

        $response = $this->authorizedPut($user, '/api/v1/user/edit', [
            'first_name'   => 'Sasuke',
            'last_name'    => 'Uchiha',
            'email'        => 'narutouzumaki@hiddenleaf.com',
            'password'     => 'admin@123',
            'address'      => 'Hidden Leaf',
            'phone_number' => '+918855663322',
            'is_marketing' => '0',
            'avatar'       => $file->getUuid(),
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'data']);

        $response->assertJsonFragment(['data' => [
            'uuid'          => $user->getUuid(),
            'first_name'    =>  'Sasuke',
            'last_name'     => 'Uchiha',
            'email'         => 'narutouzumaki@hiddenleaf.com',
            'address'       => 'Hidden Leaf',
            'phone_number'  => '+918855663322',
            'is_marketing'  => false,
            'avatar'        => [
                'uuid' => $file->getUuid(),
                'name' => $file->getName(),
                'path' => $file->getPublicPath(),
                'size' => $file->getSize(),
                'type' => $file->getType(),
            ],
            'last_login_at' => $user->getLastLoggedInAt() ? $user->getLastLoggedInAt()->toDayDateTimeString() : 'N/A'
        ]]);

        $this->assertDatabaseHas(User::class, [
            'id'           => $user->getKey(),
            'uuid'         => $user->getUuid(),
            'first_name'   => 'Sasuke',
            'last_name'    => 'Uchiha',
            'email'        => 'narutouzumaki@hiddenleaf.com',
            'address'      => 'Hidden Leaf',
            'phone_number' => '+918855663322',
            'is_marketing' => '0',
            'avatar'       => $file->getUuid(),
        ]);

        $this->assertDatabaseMissing(User::class, [
            'id'           => $user->getKey(),
            'uuid'         => $user->getUuid(),
            'first_name'   => 'Naruto',
            'last_name'    => 'Uzumakki',
            'email'        => 'narutouzumaki@hiddenleaf.com',
            'address'      => 'Hidden Leaf',
            'phone_number' => '+918855663322',
            'is_marketing' => '0',
            'avatar'       => $file->getUuid(),
        ]);
    }
}
