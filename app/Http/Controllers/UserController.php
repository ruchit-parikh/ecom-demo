<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\SaveUserFormRequest;
use App\Http\Resources\Users\UserResource;
use EcomDemo\Users\Entities\User;
use EcomDemo\Users\Repositories\Contracts\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     *
     * @return UserResource
     */
    public function show(Request $request): UserResource
    {
        /** @var User $user */
        $user = $request->user();

        return new UserResource($user);
    }

    /**
     * @param SaveUserFormRequest $request
     *
     * @return JsonResponse
     */
    public function edit(SaveUserFormRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $user = $this->userRepository->update($user, [
            'first_name'   => $request->getFirstName(),
            'last_name'    => $request->getLastName(),
            'email'        => $request->getEmail(),
            'address'      => $request->getAddress(),
            'phone_number' => $request->getPhoneNumber(),
            'is_marketing' => $request->hasMarketingPreference(),
            'avatar'       => $request->getAvatarUuid(),
        ]);

        return response()->json(['message' => __('Your profile updated successfully.'), 'data' => new UserResource($user)]);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function deleteAccount(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $this->userRepository->delete($user);

        return response()->json(['message' => __('Your account is deleted successfully.')]);
    }
}
