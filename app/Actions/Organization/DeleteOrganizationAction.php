<?php
namespace App\Actions\Organization;

use App\Models\Organization;
use League\Config\Exception\ValidationException;


final class DeleteOrganizationAction
{
    public function execute(Organization $organization): Organization
    {
        if (null !== $organization->deleted_at) {
            throw_if(null !== $organization->deleted_at, ValidationException::withMessages([
                'delete_organization' => 'Cette société est deja supprimé.',
            ]));
        }

        $organization->delete();


        return $organization;
    }
}