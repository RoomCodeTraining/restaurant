<?php

return [
    /**
     * Nombre de jours de validité d'un mot de passe.
     */
    'password_expires_days' => 90,

    /**
     * Quota maximum pour le petit déjeuner et le déjeuner.
     */
    'quota_breakfast' => 25,
    'quota_lunch' => 25,

    'menu' => [
        /**
         * L'heure avant laquelle le menu du jour peut être modifié.
         */
        'update_before' => 9
    ],

    'order' => [
        /**
         * L'heure avant laquelle le menu du jour peut être commandé.
         */
        'order_before' => 10,
        'debit_at' => '10:00'
    ]
];
