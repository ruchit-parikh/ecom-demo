<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginFormRequest;
use EcomDemo\Users\Repositories\Contracts\UserRepository;
use EcomDemo\Users\Services\TokensManager;
use Illuminate\Http\JsonResponse;
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
        $user = $this->userRepository->findByEmail($request->getEmail());

        if (!$user->isPasswordValid($request->getPass())) {
            return response()->json(['message' => 'Your given credentials do not match'], Response::HTTP_UNAUTHORIZED);
        }

        $tokens = $this->tokensManager->generateForUser($user);

        return response()->json($tokens);
    }
}
