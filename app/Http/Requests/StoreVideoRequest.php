<?php

namespace App\Http\Requests;

use App\Rules\HttpStatusCode200;
use Illuminate\Foundation\Http\FormRequest;

class StoreVideoRequest extends FormRequest
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
            'url' => [
                'bail',
                'required',
                'starts_with:https://rumble.com/v',
                'ends_with:.html',
                'active_url',
                new HttpStatusCode200,
                'unique:videos',
            ]
        ];
    }
}
