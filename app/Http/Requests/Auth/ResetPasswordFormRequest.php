<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
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
        return $this->post('email');
    }

    /**
     * @return string
     */
    public function getPass(): string
    {
        return $this->post('password');
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->post('token');
    }
}
