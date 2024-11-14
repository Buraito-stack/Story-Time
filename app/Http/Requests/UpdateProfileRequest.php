<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
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
            'current_password'  => 'required_with:new_password|string',
            'new_password'      => 'required_with:current_password|string|min:8|confirmed', 
        ];
    }

    public function messages()
    {
        return [
            'name.string'                    => 'Name must be a string.',
            'username.string'                => 'Username must be a string.',
            'username.unique'                => 'This username is already taken.',
            'email.email'                    => 'Please enter a valid email address.',
            'email.unique'                   => 'This email is already in use.',
            'about_me.string'                => 'About Me must be a string.',
            'avatar.image'                   => 'Avatar must be an image.',
            'avatar.mimes'                   => 'Avatar must be a file of type: jpeg, png, jpg, gif.',
            'avatar.max'                     => 'Avatar must not exceed 2 MB.',
            'current_password.required_with' => 'Please enter your current password to update it.',
            'new_password.string'            => 'New password must be a string.',
            'new_password.min'               => 'New password must be at least 8 characters.',
            'new_password.confirmed'         => 'The new password confirmation does not match.',
        ];
    }
}
