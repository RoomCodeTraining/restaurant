<?php

namespace App\Livewire\Account\Traits;

trait ErrorMessage
{
    public function messages(): array
    {
        return [
            'data.identifier.required' => 'Le matricule est obligatoire',
            'data.identifier.min' => 'Le matricule doit être au minimum de 5 caractères',
            'data.identifier.max' => 'Le matricule doit être au maximum de 8 caractères',
            'data.full_name.required' => 'Le nom et prénom sont obligatoires',
            'data.organization_id.required' => 'La société est obligatoire',
            'data.department_id.required' => 'Le département est obligatoire',
            'data.role.required' => 'Le role est obligatoire',
            'data.email.unique' => 'Cet email est déjà utilisé',
            'data.identifier.unique' => 'Ce matricule est déjà utilisé',
            // 'data.email.email' => 'Cet email est invalide',
            'data.contact.max' => 'Le contact ne doit pas dépasser 10 caractères',
            'data.contact.min' => 'Le contact  doit être aux min de 8 caractères',
            'data.last_name.required' => 'Le prénom est obligatoire',
            'data.email.required' => 'L\'email est obligatoire',
            'data.contact.required' => 'Le contact est obligatoire',

        ];
    }

    protected function rules()
    {
        return [
            'data.identifier' => 'required|unique:users,identifier',
            'data.full_name' => 'required|string|max:50',
            'data.organization_id' => 'required|numeric|exists:organizations,id',
            'data.department_id' => 'nullable|numeric|exists:departments,id',
            'data.role' => 'nullable|numeric|exists:roles,id',

            // 'data.last_name' => 'required|string',
            // 'data.contact' => 'required|min:8|max:10',
            // 'data.email' => 'required|email|unique:users,email',
        ];
    }
}
