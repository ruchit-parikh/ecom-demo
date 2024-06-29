<?php

namespace Tests\Feature\Users;

use EcomDemo\Files\Entities\File;
use EcomDemo\Users\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\Feature\AuthorizedTest;
use Tests\Feature\HasCustomerMiddleware;

class ShowTest extends AuthorizedTest
{
    use RefreshDatabase;
    use HasCustomerMiddleware;

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

        $this->makeAuthorizedRequest($user)
            ->assertOk()
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

    /**
     * @inheritDoc
     */
    protected function makeAuthorizedRequest(?User $user = null): TestResponse
    {
        return $this->authorizedGet('/api/v1/user', $user);
    }
}
