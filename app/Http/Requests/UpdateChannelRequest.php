<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChannelRequest extends FormRequest
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
            'name' => ['bail', 'string', 'ascii', 'max:255'],
            'description' => ['bail', 'string', 'ascii', 'max:255'],
            'banner' => ['bail', 'url', 'max:255'],
            'avatar' => ['bail', 'url', 'max:255'],
            'followers_count' => ['bail', 'integer', 'numeric', 'min:0', 'max:8000000000'],
            'videos_count' => ['bail', 'integer', 'numeric', 'min:0', 'max:65535'],
        ];
    }
}
