<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginFormRequest;
use App\Http\Requests\Auth\ResetPasswordFormRequest;
use App\Http\Requests\Users\SaveUserFormRequest;
use App\Http\Requests\Auth\SendPasswordResetLinkFormRequest;
use App\Mail\Auth\PasswordResetLinkEmail;
use App\Mail\Auth\PasswordResetSuccessfulEmail;
use Carbon\Carbon;
use DB;
use EcomDemo\Users\Entities\User;
use EcomDemo\Users\Repositories\Contracts\UserRepository;
use EcomDemo\Users\Services\TokensManager;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mail;
use stdClass;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * @var TokensManager
     */
    protected TokensManager $tokensManager;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * @param TokensManager $tokensManager
     * @param UserRepository $userRepository
     */
    public function __construct(TokensManager $tokensManager, UserRepository $userRepository)
    {
        $this->tokensManager  = $tokensManager;
        $this->userRepository = $userRepository;
    }

    /**
     * @param LoginFormRequest $request
     *
     * @return JsonResponse
     */
    public function login(LoginFormRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->userRepository->findByEmail($request->getEmail());

        if (!$user->isPasswordValid($request->getPass())) {
            return response()->json(['message' => __('Your given credentials do not match')], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $tokens = $this->tokensManager->generateForUser($user);

        $this->userRepository->refreshLastLoggedIn($user);

        return response()->json($tokens);
    }

    /**
     * @param SaveUserFormRequest $request
     *
     * @return JsonResponse
     */
    public function register(SaveUserFormRequest $request): JsonResponse
    {
        $user = $this->userRepository->create([
            'first_name'   => $request->getFirstName(),
            'last_name'    => $request->getLastName(),
            'email'        => $request->getEmail(),
            'password'     => $request->getPass(),
            'address'      => $request->getAddress(),
            'phone_number' => $request->getPhoneNumber(),
            'is_marketing' => $request->hasMarketingPreference(),
            'avatar'       => $request->getAvatarUuid(),
        ]);

        $tokens = $this->tokensManager->generateForUser($user);

        $this->userRepository->refreshLastLoggedIn($user);

        return response()->json(array_merge(['message' => __('Thank you for signing up! Your account is created successfully.')], $tokens));
    }

    /**
     * @param SendPasswordResetLinkFormRequest $request
     *
     * @return JsonResponse
     */
    public function sendPasswordResetLink(SendPasswordResetLinkFormRequest $request): JsonResponse
    {
        /** @var stdClass|null $resetAttempt */
        $resetAttempt = DB::table('password_reset_tokens')
            ->select('token')
            ->where('email', '=', $request->getEmail())
            ->latest()
            ->first();

        if ($resetAttempt) {
            Mail::to($request->getEmail())->queue(new PasswordResetLinkEmail(bcrypt($resetAttempt->token)));
        } else {
            $pin = (string) rand(100000, 999999);

            DB::table('password_reset_tokens')
                ->insert(
                    [
                        'email'      => $request->getEmail(),
                        'token'      => $pin,
                        'created_at' => Carbon::now(),
                    ]
                );

            Mail::to($request->getEmail())->queue(new PasswordResetLinkEmail(bcrypt($pin)));
        }

        return response()->json(['message' => __('Password reset link has been sent on your email. Please check your mail box.')]);
    }

    /**
     * @param ResetPasswordFormRequest $request
     *
     * @return JsonResponse
     */
    public function resetPassword(ResetPasswordFormRequest $request): JsonResponse
    {
        $token = $request->getToken();

        /** @var stdClass|null $resetAttempt */
        $resetAttempt = DB::table('password_reset_tokens')
            ->select('token')
            ->where('email', '=', $request->getEmail())
            ->latest()
            ->first();

        if (!$resetAttempt || !Hash::check($resetAttempt->token, urldecode($token))) {
            return response()->json(['message' => __('Provided token doesnt match for password reset or is expired')], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->userRepository->updatePassword($request->getEmail(), $request->getPass());

        DB::table('password_reset_tokens')
            ->where('email', '=', $request->getEmail())
            ->delete();

        Mail::to($request->getEmail())->queue(new PasswordResetSuccessfulEmail());

        return response()->json(['message' => __('Password reset successful. You can login using new password now.')]);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        /** @var string $bearer */
        $bearer = $request->bearerToken();

        $this->tokensManager->invalidate($bearer);

        return response()->json(['message' => __('You are logged out successfully')]);
    }
}
