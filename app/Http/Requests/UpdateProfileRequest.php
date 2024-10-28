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
            'name'              => 'required|string|max:255',
            'username'          => 'required|string|max:255|unique:users,username,' . $this->user()->id,
            'email'             => 'required|email|max:255|unique:users,email,' . $this->user()->id,
            'about_me'          => 'required|string|max:500',
            'avatar'            => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password'  => 'required|string',
            'new_password'      => 'required|string|min:8|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'new_password.confirmed' => 'The new password confirmation does not match.',
        ];
    }
}
