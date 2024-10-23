<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'name'              => 'nullable|string|max:255',
            'username'          => 'nullable|string|max:255|unique:users,username,' . $this->user()->id,
            'email'             => 'nullable|email|max:255|unique:users,email,' . $this->user()->id,
            'about_me'          => 'nullable|string|max:500',
            'avatar'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password'  => 'nullable|string',
            'new_password'      => 'nullable|string|min:8|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'new_password.confirmed' => 'The new password confirmation does not match.',
        ];
    }
}
