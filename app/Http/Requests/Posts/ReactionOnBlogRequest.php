<?php

namespace App\Http\Requests\Posts;

use Illuminate\Foundation\Http\FormRequest;

class ReactionOnBlogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'required|string|in:like,dislike',
        ];
    }
    public function messages(): array
    {
        return [
            'type.required' => 'The reaction type is required.',
            'type.in' => 'The reaction type must be like or dislike.',
        ];
    }
}
