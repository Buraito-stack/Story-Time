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
        $isUpdate = $this->route('story') !== null;
    
        return [
            'title'         => $isUpdate ? 'sometimes|string|max:255' : 'required|string|max:255',
            'category'      => $isUpdate ? 'sometimes|exists:categories,id' : 'required|exists:categories,id',
            'content'       => $isUpdate ? 'sometimes|string' : 'required|string',
            'cover_image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:51200',
        ];
    }
}
