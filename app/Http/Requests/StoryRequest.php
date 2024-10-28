<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255', 
            'category'    => 'required|exists:categories,id', 
            'content'     => 'required|string', 
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ];
    }
}
