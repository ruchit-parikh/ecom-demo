<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email'    => 'required|email|max:255|exists:users,email',
            'password' => 'required|min:8|confirmed|max:255',
            'token'    => 'required',
        ];
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        /** @var string $email */
        $email = $this->post('email');

        return $email;
    }

    /**
     * @return string
     */
    public function getPass(): string
    {
        /** @var string $password */
        $password = $this->post('password');

        return $password;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        /** @var string $token */
        $token = $this->post('token');

        return $token;
    }
}
