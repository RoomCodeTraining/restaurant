<?php

return [
    'frequencies'  => [
        [
            'label'             => 'Chaque minute',
            'interval'          => 'everyMinute',
            'parameters'        => false,
        ],
        [
            'label'             => 'Chaque cinq minutes',
            'interval'          => 'everyFiveMinutes',
            'parameters'        => false,
        ],
        [
            'label'             => 'Toutes les 10 minutes',
            'interval'          => 'everyTenMinutes',
            'parameters'        => false,
        ],
        [
            'label'             => 'Toutes les 30 minutes',
            'interval'          => 'everyThirtyMinutes',
            'parameters'        => false,
        ],
        [
            'label'             => 'Toutes les heures',
            'interval'          => 'hourly',
            'parameters'        => false,
        ],
        [
            'label'             => 'Toutes les heures à',
            'interval'          => 'hourlyAt',
            'parameters'        => [
                [
                    'label'         => 'At',
                    'name'          => 'at',
                    'type'          => 'number',
                    'min'           => '0',
                    'max'           => '59',
                ],
            ],
        ],
        [
            'label'             => 'Tout les jours',
            'interval'          => 'daily',
            'parameters'        => false,
        ],
        [
            'label'             => 'Tout les jours à',
            'interval'          => 'dailyAt',
            'parameters'        => [
                [
                    'label'         => 'At',
                    'name'          => 'at',
                    'type'          => 'time',
                ],
            ],
        ],
        [
            'label'             => 'Deux par jour',
            'interval'          => 'twiceDaily',
            'parameters'        => [
                [
                    'label'         => 'First',
                    'name'          => 'at',
                    'type'          => 'time',
                ],
                [
                    'label'         => 'Second',
                    'name'          => 'second_at',
                    'type'          => 'time',
                ],
            ],
        ],
        [
            'label'             => 'Hebdomadaire',
            'interval'          => 'weekly',
            'parameters'        => false,
        ],
        [
            'label'             => 'Chaque semaine le',
            'interval'          => 'weeklyOn',
            'parameters'        => [
                [
                    'label'         => 'On',
                    'name'          => 'on',
                    'type'          => 'number',
                    'min'           => '1',
                    'max'           => '31',
                ],
                [
                    'label'         => 'At',
                    'name'          => 'at',
                    'type'          => 'time',
                ],
            ],
        ],
        [
            'label'             => 'Tout les mois',
            'interval'          => 'monthly',
            'parameters'        => false,
        ],
        [
            'label'             => 'Chaque mois le',
            'interval'          => 'monthlyOn',
            'parameters'        => [
                [
                    'label'         => 'On',
                    'name'          => 'on',
                    'type'          => 'number',
                    'max'           => '',
                ],
                [
                    'label'         => 'At',
                    'name'          => 'at',
                    'type'          => 'time',
                ],
            ],
        ],
        [
            'label'             => 'Tout les deux mois',
            'interval'          => 'twiceMonthly',
            'parameters'        => [
                [
                    'label'         => 'First',
                    'name'          => 'on',
                    'type'          => 'number',
                ],
                [
                    'label'         => 'Second',
                    'name'          => 'second_at',
                    'type'          => 'text',
                ],
            ],
        ],
        [
            'label'             => 'Chaque trimestre',
            'interval'          => 'quarterly',
            'parameters'        => false,
        ],
        [
            'label'             => 'annuel',
            'interval'          => 'yearly',
            'parameters'        => false,
        ],
        [
            'label'             => 'jours de la semaine',
            'interval'          => 'weekdays',
            'parameters'        => false,
        ],
        [
            'label'             => 'Tout les dimanche',
            'interval'          => 'sundays',
            'parameters'        => false,
        ],
        [
            'label'             => 'Tout les lundi',
            'interval'          => 'mondays',
            'parameters'        => false,
        ],
        [
            'label'             => 'Tout les mardi',
            'interval'          => 'tuesdays',
            'parameters'        => false,
        ],
        [
            'label'             => 'Tout les mercredi',
            'interval'          => 'wednesdays',
            'parameters'        => false,
        ],
        [
            'label'             => 'Tout les Jeudi',
            'interval'          => 'thursdays',
            'parameters'        => false,
        ],
        [
            'label'             => 'Tout les Vendredi',
            'interval'          => 'fridays',
            'parameters'        => false,
        ],
        [
            'label'             => 'Tout les samedi',
            'interval'          => 'saturdays',
            'parameters'        => false,
        ],
        [
            'label'             => 'Compris entre',
            'interval'          => 'between',
            'parameters'        => [
                [
                    'label'         => 'Start',
                    'name'          => 'start',
                    'type'          => 'time',
                ],
                [
                    'label'         => 'End',
                    'name'          => 'end',
                    'type'          => 'time',
                ],
            ],
        ],
        [
            'label'             => 'Sauf entre',
            'interval'          => 'unlessBetween',
            'parameters'        => [
                [
                    'label'         => 'Start',
                    'name'          => 'start',
                    'type'          => 'time',
                ],
                [
                    'label'         => 'End',
                    'name'          => 'end',
                    'type'          => 'time',
                ],
            ],
        ],
    ],
    'web' => [
        'middleware' => env('TOTEM_WEB_MIDDLEWARE', 'web'),
        'route_prefix' => env('TOTEM_WEB_ROUTE_PREFIX', 'totem'),
    ],
    'api' => [
        'middleware' => env('TOTEM_API_MIDDLEWARE', 'api'),
    ],
    'table_prefix' => env('TOTEM_TABLE_PREFIX', ''),
    'artisan' => [
      'command_filter' => [
          'charge:users',
          'orders:generate-breakfast',
          'cards:delete-temporary',
      ],
      'whitelist' => true,
  ],
  
    'database_connection' => env('TOTEM_DATABASE_CONNECTION'),

    'broadcasting' => [
        'enabled' => env('TOTEM_BROADCASTING_ENABLED', true),
        'channel' => env('TOTEM_BROADCASTING_CHANNEL', 'task.events'),
    ],
];
