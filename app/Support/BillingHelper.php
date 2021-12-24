<?php

namespace App\Support;

use App\Models\User;

class BillingHelper
{
    public const USER_TYPE_AGENT_CIPREL = 1;
    public const USER_TYPE_AGENT_NON_CIPREL = 2;
    public const USER_TYPE_INVITEE = 3;
    public const USER_TYPE_STAGIAIRE = 4;

    public const EMP_STATUS_CADRE = 1;
    public const EMP_STATUS_MAITRISE = 2;
    public const EMP_STATUS_STAGIAIRE = 3;

    protected $billMap = [];

    public function __construct()
    {
        $this->billMap = [
            self::USER_TYPE_STAGIAIRE => [
                self::EMP_STATUS_STAGIAIRE => [
                    'subvention' => [
                        'lunch' => 3500,
                        'breakfast' => 500,
                    ],
                    'contribution' => [
                        'lunch' => 0,
                        'breakfast' => 0,
                    ],
                ],
            ],
            self::USER_TYPE_INVITEE => [
                self::EMP_STATUS_CADRE => [
                    'subvention' => [
                        'lunch' => 0,
                        'breakfast' => 0,
                    ],
                    'contribution' => [
                        'lunch' => 0,
                        'breakfast' => 0,
                    ],
                ],
                self::EMP_STATUS_MAITRISE => [
                    'subvention' => [
                        'lunch' => 0,
                        'breakfast' => 0,
                    ],
                    'contribution' => [
                        'lunch' => 0,
                        'breakfast' => 0,
                    ],
                ],
                self::EMP_STATUS_STAGIAIRE => [
                    'subvention' => [
                        'lunch' => 0,
                        'breakfast' => 0,
                    ],
                    'contribution' => [
                        'lunch' => 0,
                        'breakfast' => 0,
                    ],
                ],
            ],
            self::USER_TYPE_AGENT_NON_CIPREL => [
                self::EMP_STATUS_CADRE => [
                    'subvention' => [
                        'lunch' => 3250,
                        'breakfast' => 250,
                    ],
                    'contribution' => [
                        'lunch' => 250,
                        'breakfast' => 250,
                    ],
                ],
                self::EMP_STATUS_MAITRISE => [
                    'subvention' => [
                        'lunch' => 3400,
                        'breakfast' => 400,
                    ],
                    'contribution' => [
                        'lunch' => 100,
                        'breakfast' => 100,
                    ],
                ],
                self::EMP_STATUS_STAGIAIRE => [
                    'subvention' => [
                        'lunch' => 0,
                        'breakfast' => 0,
                    ],
                    'contribution' => [
                        'lunch' => 0,
                        'breakfast' => 0,
                    ],
                ],
            ],
            self::USER_TYPE_AGENT_CIPREL => [
                self::EMP_STATUS_CADRE => [
                    'subvention' => [
                        'lunch' => 3250,
                        'breakfast' => 250,
                    ],
                    'contribution' => [
                        'lunch' => 250,
                        'breakfast' => 250,
                    ],
                ],
                self::EMP_STATUS_MAITRISE => [
                    'subvention' => [
                        'lunch' => 3400,
                        'breakfast' => 400,
                    ],
                    'contribution' => [
                        'lunch' => 100,
                        'breakfast' => 100,
                    ],
                ],
                self::EMP_STATUS_STAGIAIRE => [
                    'subvention' => [
                        'lunch' => 0,
                        'breakfast' => 0,
                    ],
                    'contribution' => [
                        'lunch' => 0,
                        'breakfast' => 0,
                    ],
                ],
            ],
        ];
    }

    public static function getUserBill(User $user, $period): array
    {
        $billingHelper = new self();

        $lunchCount = $user->actions()
            ->where('event', 'decrement_quota_lunch')
            ->whereBetween('created_at', DateTimeHelper::inThePeriod($period))
            ->count();

        $breakfastCount = $user->actions()
            ->where('event', 'decrement_quota_breakfast')
            ->whereBetween('created_at', DateTimeHelper::inThePeriod($period))
            ->count();

        $lunchBill = max($billingHelper->billMap[$user->user_type_id][$user->employee_status_id]['contribution']['lunch'], 1) * $lunchCount;
        $breakfastBill = max($billingHelper->billMap[$user->user_type_id][$user->employee_status_id]['contribution']['breakfast'], 1) * $breakfastCount;

        $subventionLunchBill = $billingHelper->billMap[$user->user_type_id][$user->employee_status_id]['subvention']['lunch'] * $lunchCount;
        $subventionBreakfastBill = $billingHelper->billMap[$user->user_type_id][$user->employee_status_id]['subvention']['breakfast'] * $breakfastCount;

        return ['contribution' => $lunchBill + $breakfastBill, 'subvention' => $subventionLunchBill + $subventionBreakfastBill];
    }
}
