<?php

namespace App\Actions\UserType;

use App\Models\UserType;

class UpdateUserTypeAction
{
    public function execute(UserType $userType, array $data): UserType
    {
        $userType->update([
            'name' => $data['name'],
            'auto_identifier' => $data['auto_identifier']
        ]);

        return $userType->fresh();
    }
}
