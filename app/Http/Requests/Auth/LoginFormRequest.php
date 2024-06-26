<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email'    => 'required|email|exists:users,email|max:255',
            'password' => 'required|max:255'
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
}
