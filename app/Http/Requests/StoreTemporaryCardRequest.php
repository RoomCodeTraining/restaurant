<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTemporaryCardRequest extends FormRequest
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
          'access_card_identifier' => ['required', 'string', 'max:255'],
          'expires_at' => ['nullable', Rule::requiredIf((bool) $this->is_temporary), 'date', 'after_or_equal:today'],
        ];
    }
}