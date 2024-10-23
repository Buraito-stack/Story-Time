<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $this->route('user'),
            'email'    => 'required|email|max:255|unique:users,email,' . $this->route('user'),
            'password' => 'sometimes|required|string|min:8|confirmed', 
            'about_me' => 'nullable|string',
        ];
    }

    public function authorize(): bool
    {
        return true; 
    }
}
