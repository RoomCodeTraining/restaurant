<?php

namespace App\Enums;

use Essa\APIToolKit\Enum\Enum;

class SuggestionType extends Enum
{
    public const IMPROVEMENT_APPLICATION = 1;
    public const BUG_REPORT = 2;
    public const IMPROVEMENT_DISH = 3;
    public const IMPROVEMENT_MENU = 4;
    public const IMPROVEMENT_DELIVERY = 5;
    public const OTHER = 6;
}