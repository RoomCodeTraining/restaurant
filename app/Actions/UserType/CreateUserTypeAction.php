<?php

namespace App\Actions\UserType;

use App\Events\UserTypeCreated;
use App\Models\UserType;

class CreateUserTypeAction
{
    public function execute(array $data): UserType
    {
        $userType = UserType::create([
            'name' => $data['name'],
            'auto_identifier' => $data['auto_identifier']
        ]);

        UserTypeCreated::dispatch($userType);

        return $userType;
    }
}
