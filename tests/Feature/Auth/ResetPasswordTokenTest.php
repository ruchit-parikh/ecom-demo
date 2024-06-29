<?php

namespace Tests\Feature\Auth;

use App\Mail\Auth\PasswordResetSuccessfulEmail;
use Carbon\Carbon;
use DB;
use EcomDemo\Users\Entities\User;
use Mail;
use Tests\TestCase;

class ResetPasswordTokenTest extends TestCase
{
    public function test_user_can_reset_password_with_valid_token()
    {
        /** @var User $user */
        $user  = User::factory()->customer()->create();
        $pin   = '123456';

        DB::table('password_reset_tokens')->insert([
            'email'      => $user->getEmail(),
            'token'      => $pin,
            'created_at' => Carbon::now()
        ]);

        $response = $this->postJson('/api/v1/user/reset-password-token', [
            'email'                 => $user->getEmail(),
            'token'                 => urlencode(bcrypt($pin)),
            'password'              => 'newpassword',
            'password_confirmation' => 'newpassword'
        ]);

        $response->assertOk()
            ->assertJsonStructure(['message']);

        Mail::assertQueued(PasswordResetSuccessfulEmail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->getEmail());
        });

        $this->assertTrue($user->fresh()->isPasswordValid('newpassword'));
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->getEmail(), 'token' => $pin]);
    }

    public function test_user_cannot_reset_password_with_invalid_token()
    {
        /** @var User $user */
        $user  = User::factory()->customer()->create();
        $pin   = '123456';

        DB::table('password_reset_tokens')->insert([
            'email'      => $user->getEmail(),
            'token'      => $pin,
            'created_at' => Carbon::now()
        ]);

        $response = $this->postJson('/api/v1/user/reset-password-token', [
            'email'                 => $user->getEmail(),
            'token'                 => urlencode(bcrypt('987654')),
            'password'              => 'newpassword',
            'password_confirmation' => 'newpassword'
        ]);

        $response->assertUnprocessable()
            ->assertJsonStructure(['message']);

        Mail::assertNotQueued(PasswordResetSuccessfulEmail::class);

        $this->assertFalse($user->fresh()->isPasswordValid('newpassword'));
        $this->assertDatabaseHas('password_reset_tokens', ['email' => $user->getEmail(), 'token' => $pin]);
    }

    public function test_user_cannot_reset_password_without_token()
    {
        /** @var User $user */
        $user  = User::factory()->customer()->create();
        $pin   = '123456';

        DB::table('password_reset_tokens')->insert([
            'email'      => $user->getEmail(),
            'token'      => $pin,
            'created_at' => Carbon::now()
        ]);

        $response = $this->postJson('/api/v1/user/reset-password-token', [
            'email'                 => $user->getEmail(),
            'password'              => 'newpassword',
            'password_confirmation' => 'newpassword'
        ]);

        $response->assertUnprocessable()
            ->assertJsonStructure(['message']);

        Mail::assertNotQueued(PasswordResetSuccessfulEmail::class);

        $this->assertFalse($user->fresh()->isPasswordValid('newpassword'));
        $this->assertDatabaseHas('password_reset_tokens', ['email' => $user->getEmail(), 'token' => $pin]);
    }

    public function test_admin_cannot_reset_password()
    {
        /** @var User $user */
        $user  = User::factory()->admin()->create();
        $pin   = '123456';

        DB::table('password_reset_tokens')->insert([
            'email'      => $user->getEmail(),
            'token'      => $pin,
            'created_at' => Carbon::now()
        ]);

        $response = $this->postJson('/api/v1/user/reset-password-token', [
            'token'                 => urlencode(bcrypt('123456')),
            'email'                 => $user->getEmail(),
            'password'              => 'newpassword',
            'password_confirmation' => 'newpassword'
        ]);

        $response->assertForbidden()
            ->assertJsonStructure(['message']);

        Mail::assertNotQueued(PasswordResetSuccessfulEmail::class);

        $this->assertFalse($user->fresh()->isPasswordValid('newpassword'));
        $this->assertDatabaseHas('password_reset_tokens', ['email' => $user->getEmail(), 'token' => $pin]);
    }
}
