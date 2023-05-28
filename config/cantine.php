<?php

return [
    /**
     * Nombre de jours de validité d'un mot de passe.
     */
    'password_expires_days' => env('PASSWORD_EXPIRE_DAYS', 90),

    /**
     * Quota maximum pour le petit déjeuner et le déjeuner.
     */
    'quota_breakfast' => env('QUOTA_MAX', 25),
    'quota_lunch' => env('QUOTA_MAX', 25),

    'menu' => [
        /**
         * L'heure avant laquelle le menu du jour peut être modifié.
         */
        'locked_at' => env('MENU_LOCKED_AT', 9)
    ],

    'order' => [
        /**
         * L'heure avant laquelle le menu du jour peut être commandé.
         */
        'locked_at' => env('ORDER_LOCKED_AT', 00),
        'charge_at' => env('CHARGE_USER_AT', env('ORDER_LOCKED_AT').':00')
    ]
];
