<?php

namespace App\Http\Requests\Users;

use EcomDemo\Users\Entities\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SaveUserFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var User|null $user */
        $user = auth()->user();

        return [
            'first_name'   => 'required|max:255',
            'last_name'    => 'required|max:255',
            'email'        => 'required|email|max:255|unique:users,email' . ($user ? ',' . $user->getKey() : ''),
            'password'     => $user ? 'nullable' : 'required|min:8|confirmed|max:255',
            'address'      => 'required|max:255',
            'phone_number' => 'required|max:255',
            'is_marketing' => 'nullable',
            'avatar'       => 'nullable|max:255|exists:files,uuid',
        ];
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->input('first_name');
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->input('last_name');
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->input('email');
    }

    /**
     * Password is only for non-registered user else through reset password
     *
     * @return string
     */
    public function getPass(): string
    {
        return $this->input('password');
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->input('address');
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->input('phone_number');
    }

    /**
     * @return bool
     */
    public function hasMarketingPreference(): bool
    {
        return !empty($this->input('is_marketing'));
    }

    /**
     * @return string|null
     */
    public function getAvatarUuid(): ?string
    {
        return $this->input('avatar');
    }
}
