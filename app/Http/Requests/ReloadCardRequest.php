<?php

namespace App\Http\Requests;

use App\Models\AccessCard;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReloadCardRequest extends FormRequest
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
            'access_card_identifier' => [
                'required',
                function ($attrubute, $value, $fail) {
                    if (! AccessCard::whereIdentifier($value)) {
                        $fail("Cette Carte RFID n'existe pas. Veuillez verifier l'identifiant entré");
                    }
                },
            ],
            'quota_type' => ['required', 'string', Rule::in(['quota_lunch', 'quota_breakfast'])],
        ];
    }
}