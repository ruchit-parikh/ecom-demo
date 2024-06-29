<?php

namespace Tests\Feature\Users;

use EcomDemo\Files\Entities\File;
use EcomDemo\Users\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_if_can_see_current_user()
    {
        /** @var File $file */
        $file = File::factory()->create();

        /** @var User $user */
        $user = User::factory()->customer()->create([
            'first_name'   => 'Tayara',
            'last_name'    => 'Capta',
            'email'        => 'tayracapta@japan.com',
            'address'      => 'Japan',
            'phone_number' => '+919988776655',
            'is_marketing' => '0',
            'avatar'       => $file->getUuid()
        ]);

        $this->authorizedGet($user, '/api/v1/user')
            ->assertStatus(200)
            ->assertJson(['data' => [
                'uuid'          => $user->getUuid(),
                'first_name'    =>  'Tayara',
                'last_name'     => 'Capta',
                'email'         => 'tayracapta@japan.com',
                'address'       => 'Japan',
                'phone_number'  => '+919988776655',
                'is_marketing'  => '0',
                'avatar'        => [
                    'uuid' => $file->getUuid(),
                    'name' => $file->getName(),
                    'path' => $file->getPublicPath(),
                    'size' => $file->getSize(),
                    'type' => $file->getType(),
                ],
                'last_login_at' => $user->getLastLoggedInAt() ? $user->getLastLoggedInAt()->toDayDateTimeString() : 'N/A'
            ]]);
    }
}
