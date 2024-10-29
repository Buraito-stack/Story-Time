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
            'name'              => 'string|max:255',
            'username'          => 'string|max:255|unique:users,username,' . $this->user()->id,
            'email'             => 'email|max:255|unique:users,email,' . $this->user()->id,
            'about_me'          => 'string|max:500',
            'avatar'            => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password'  => 'string',
            'new_password'      => 'string|min:8|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'new_password.confirmed' => 'The new password confirmation does not match.',
        ];
    }
}
