<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Collection;

class BillingHelper
{
    public const USER_TYPE_AGENT_CIPREL = 1;
    public const USER_TYPE_AGENT_NON_CIPREL = 2;
    public const USER_TYPE_INVITEE = 3;
    public const USER_TYPE_STAGIAIRE = 4;
    public const USER_TYPE_PROVIDER = 5;

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
                        'lunch' => 3000,
                        'breakfast' => 500,
                    ],
                    'contribution' => [
                        'lunch' => 500,
                        'breakfast' => 0,
                    ],
                ],
                self::EMP_STATUS_MAITRISE => [
                    'subvention' => [
                        'lunch' => 3300,
                        'breakfast' => 500,
                    ],
                    'contribution' => [
                        'lunch' => 200,
                        'breakfast' => 0,
                    ],
                ],
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

            self::USER_TYPE_PROVIDER => [
                self::EMP_STATUS_CADRE => [
                    'subvention' => [
                        'lunch' => 3000,
                        'breakfast' => 500,
                    ],
                    'contribution' => [
                        'lunch' => 500,
                        'breakfast' => 0,
                    ],
                ],

                self::EMP_STATUS_MAITRISE => [

                    'subvention' => [
                        'lunch' => 3300,
                        'breakfast' => 500,
                    ],
                    'contribution' => [
                        'lunch' => 200,
                        'breakfast' => 0,
                    ],
                ],

            ]

        ];
    }


    public static function getUserLunchContribution(User $user, $type = 'lunch')
    {
        $billingHelper = new self();
        if (! isset($billingHelper->billMap[$user->user_type_id]) || ! isset($billingHelper->billMap[$user->user_type_id][$user->employee_status_id])) {
            throw new \Exception("User type or employee status not found");
        }

        $billMap = $billingHelper->billMap[$user->user_type_id][$user->employee_status_id];

        if($type == 'lunch') {
            $bill['subvention'] = $billMap['subvention']['lunch'];
            $bill['contribution'] = $billMap['contribution']['lunch'];
        } else {
            $bill['subvention'] = $billMap['subvention']['breakfast'];
            $bill['contribution'] = $billMap['contribution']['breakfast'];
        }

        return $bill;
    }

    public static function getUserBill(User $user, $order): Collection
    {
        $billingHelper = new self();

        if (! isset($billingHelper->billMap[$user->user_type_id]) || ! isset($billingHelper->billMap[$user->user_type_id][$user->employee_status_id])) {
            throw new \Exception("User type or employee status not found");
        }

        $billMap = $billingHelper->billMap[$user->user_type_id][$user->employee_status_id];
        $type = $order[0]['type'] ?? $order->type;
        if($type == 'lunch') {
            $bill['subvention'] = $billMap['subvention']['lunch'];
            $bill['contribution'] = $billMap['contribution']['lunch'];
        } else {
            $bill['subvention'] = $billMap['subvention']['breakfast'];
            $bill['contribution'] = $billMap['contribution']['breakfast'];
        }


        return collect($bill);
    }
}