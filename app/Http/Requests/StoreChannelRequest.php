<?php

namespace App\Http\Requests;

use App\Rules\HttpStatusCode200;
use App\Rules\HasAboutPage;
use Illuminate\Foundation\Http\FormRequest;

class StoreChannelRequest extends FormRequest
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
                'starts_with:https://rumble.com/c/',
                'doesnt_end_with:/,/about', 
                'unique:channels',
                'active_url',
                new HttpStatusCode200,
                new HasAboutPage, 
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'url.unique' => 'This channel already exists.',
        ];
    }
}
