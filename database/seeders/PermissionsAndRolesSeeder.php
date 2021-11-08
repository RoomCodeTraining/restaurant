<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

/**
 * Class PermissionRoleTableSeeder.
 */
class PermissionsAndRolesSeeder extends Seeder
{
    /**
     * Run the database seed.
     */
    public function run()
    {
        $users = Permission::create([
            'name' => 'user.*',
            'description' => 'Toutes les permissions relatives aux utilisateurs',
        ]);

        $users->children()->saveMany([
            new Permission([
                'name' => 'user.list',
                'description' => 'Voir la liste des utilisateurs',
            ]),
            new Permission([
                'name' => 'user.create',
                'description' => 'Créer un utilisateur',
            ]),
            new Permission([
                'name' => 'user.update',
                'description' => 'Modifier un utilisateur',
            ]),
            new Permission([
                'name' => 'user.delete',
                'description' => 'Désactiver un utilisateur',
            ]),
        ]);

        $activities = Permission::create([
            'name' => 'activity.*',
            'description' => 'Toutes les permissions relatives aux activités',
        ]);

        $activities->children()->saveMany([
            new Permission([
                'name' => 'activity.list',
                'description' => 'Voir la liste des activités',
            ]),
        ]);

        $accessCards = Permission::create([
            'name' => 'access_card.*',
            'description' => 'Toutes les permissions relatives a la gestion des cartes d\'accès'
        ]);

        $accessCards->children()->saveMany([
            new Permission([
                'name' => 'access_card.list',
                'description' => 'Voir la liste des cartes d\'accès',
            ]),
            new Permission([
                'name' => 'access_card.charge',
                'description' => 'Recharger une carte d\'accès',
            ]),
        ]);

        $menus = Permission::create([
            'name' => 'menu.*',
            'description' => 'Toutes les permissions relatives a la gestion des menus'
        ]);

        $menus->children()->saveMany([
            new Permission([
                'name' => 'menu.list',
                'description' => 'Voir la liste des commlandes',
            ]),
            new Permission([
                'name' => 'menu.create',
                'description' => 'Ajouter un menu'
            ]),
            new Permission([
                'name' => 'menu.update',
                'description' => 'Modifier un menu'
            ]),
            new Permission([
                'name' => 'menu.delete',
                'description' => 'Supprimer un menu'
            ])
        ]);

        $orders = Permission::create([
            'name' => 'order.*',
            'description' => 'Toutes les permissions relatives aux commandes'
        ]);

        $orders->children()->saveMany([
            new Permission([
                'name' => 'order.list',
                'description' => 'Voir la liste des commandes',
            ]),
            new Permission([
                'name' => 'order.update',
                'description' => 'Modifier les commandes'
            ]),
            new Permission([
                'name' => 'order.cancel',
                'description' => 'Annulation d\'une commande en cours'
            ]),
            new Permission([
                'name' => 'order.create',
                'description' => 'Passer une commande'
            ]),
            new Permission([
                'name' => 'order.confirm',
                'description' => 'Valider une commande via une carte RFID',
            ]),
            new Permission([
                'name' => 'order.validate',
                'description' => 'Valider la commande de l\'utilisateur'
            ])
        ]);


        Role::create([
            'id' => Role::SUPER_ADMIN,
            'name' => 'Super Administrateur',
        ])->givePermissionTo([
            'user.*',
            'activity.*',
            'access_card.*',
            'menu.*',
            'order.*',
        ]);

        Role::create([
            'id' => Role::USER,
            'name' => 'Collaborateur',
        ])->givePermissionTo([
            'order.*',
            'menu.list'
        ]);

        Role::create([
            'id' => Role::ADMIN_RH,
            'name' => 'Admin Resource Humaine',
        ])->givePermissionTo([
            'order.*',
            'menu.list',
            'access_card.*'
        ]);

        Role::create([
            'id' => Role::ADMIN_LUNCHROOM,
            'name' => 'Admin Cantine',
        ])->givePermissionTo([
            'order.*',
            'menu.*',
            'access_card.*',
        ]);

        Role::create([
            'id' => Role::ADMIN_ACCOUNTANT,
            'name' => 'Admin comptable',
        ])->givePermissionTo([
            'order.*',
            'menu.list',
        ]);

        Role::create([
            'id' => Role::OPERATOR_LUNCHROOM,
            'name' => 'Operateur Cantine',
        ])->givePermissionTo([
            'menu.list',
            'order.*',
        ]);
    }
}
