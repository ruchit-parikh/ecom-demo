<?php

namespace Tests\Feature\Auth;

use App\Mail\Auth\PasswordResetLinkEmail;
use Carbon\Carbon;
use DB;
use EcomDemo\Users\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mail;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_send_password_reset_link()
    {
        /** @var User $user */
        $user = User::factory()->customer()->create();

        $response = $this->postJson('/api/v1/user/forgot-password', ['email' => $user->getEmail()]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message']);

        Mail::assertQueued(PasswordResetLinkEmail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->getEmail());
        });

        $this->assertDatabaseHas('password_reset_tokens', ['email' => $user->getEmail()]);
    }

    public function test_resend_same_password_reset_link_when_user_retry()
    {
        /** @var User $user */
        $user = User::factory()->customer()->create();
        $pin  = '987654';

        DB::table('password_reset_tokens')
            ->insert(['email' => $user->getEmail(), 'token' => $pin, 'created_at' => Carbon::now()]);

        $response = $this->postJson('/api/v1/user/forgot-password', ['email' => $user->getEmail()]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message']);

        Mail::assertQueued(PasswordResetLinkEmail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->getEmail());
        });

        $last = DB::table('password_reset_tokens')->orderBy('created_at', 'desc')->first();

        $this->assertEquals($pin, $last->token);
    }

    public function test_admin_cannot_send_password_reset_link()
    {
        /** @var User $user */
        $user = User::factory()->admin()->create();

        $response = $this->postJson('/api/v1/user/forgot-password', ['email' => $user->getEmail()]);

        $response->assertStatus(403)
            ->assertJsonStructure(['message']);

        Mail::assertNotQueued(PasswordResetLinkEmail::class);
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->getEmail()]);
    }
}
