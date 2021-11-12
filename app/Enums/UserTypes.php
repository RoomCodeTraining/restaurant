<?php

namespace App\Enums;

class UserTypes
{
    const GUEST = 1;
    const INTERN = 2;
    const CIPREL_AGENT = 3;
    const NON_CIPREL_AGENT = 4;

    public static function getUserType($type)
    {
        switch ($type) {
            case self::GUEST:
                return 'Invité';
            case self::INTERN:
                return 'Stagiaire';
            case self::CIPREL_AGENT:
                return 'Agent CIPREL';
            case self::NON_CIPREL_AGENT:
                return 'Agent Non CIPREL';
            default:
                return 'Inconnu';
        }
    }

    public static function getUserTypes()
    {
        return [
            self::GUEST => 'Invité',
            self::INTERN => 'Stagiaire',
            self::CIPREL_AGENT => 'Agent CIPREL',
            self::NON_CIPREL_AGENT => 'Agent Non CIPREL',
        ];
    }
}
