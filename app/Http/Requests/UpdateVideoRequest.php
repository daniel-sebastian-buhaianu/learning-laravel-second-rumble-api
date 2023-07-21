<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVideoRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'src' => ['string', 'max:255'],
            'name' => ['string', 'max:255'],
            'thumbnail' => ['string', 'max:255'],
            'description' => ['string'],
            'likes_count' => ['integer', 'numeric', 'min:0', 'max:65535'],
            'dislikes_count' => ['integer', 'numeric', 'min:0', 'max:65535'],
            'comments_count' => ['integer', 'numeric', 'min:0', 'max:65535'],
            'views_count' => ['integer', 'numeric', 'min:0'],
        ];
    }
}
