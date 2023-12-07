<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCurrentCardRequest extends FormRequest
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
            'user_id' => ['required', Rule::exists('users', 'id')],
            'identifier' => ['required', 'string', 'max:255', Rule::unique('access_cards', 'identifier')],
            'quota_breakfast' => ['nullable', 'integer', 'min:0', 'max:25'],
            'quota_lunch' => ['nullable', 'integer', 'min:0', 'max:25'],
            'payment_method_id' => ['nullable', Rule::exists('payment_methods', 'id')],
        ];
    }
}