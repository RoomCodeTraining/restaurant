<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use App\Policies\AccessCardPolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\MenuPolicy;
use App\Policies\OrderPolicy;
use App\Policies\OrganizationPolicy;
use App\Policies\UserPolicy;
use Illuminate\Database\Seeder;

/**
 * Class PermissionRoleTableSeeder.
 */
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seed.
     */
    public function run()
    {
        $users = Permission::create([
            'name' => UserPolicy::USER_MANAGE,
            'description' => 'Toutes les permissions relatives à la gestion des utilisateurs',
        ]);

        $users->children()->saveMany([
            new Permission([
                'name' => UserPolicy::USER_LIST,
                'description' => 'Voir la liste des utilisateurs',
            ]),
            new Permission([
                'name' => UserPolicy::USER_CREATE,
                'description' => 'Créer un utilisateur',
            ]),
            new Permission([
                'name' => UserPolicy::USER_UPDATE,
                'description' => 'Modifier un utilisateur',
            ]),
            new Permission([
                'name' => UserPolicy::USER_DELETE,
                'description' => 'Supprimer un utilisateur',
            ]),
            new Permission([
                'name' => UserPolicy::USER_DEACTIVATE,
                'description' => 'Désactiver un utilisateur',
            ]),
        ]);

        $accessCards = Permission::create([
            'name' => AccessCardPolicy::ACCESS_CARD_MANAGE,
            'description' => "Toutes les permissions relatives à la gestion des cartes d'accès"
        ]);

        $accessCards->children()->saveMany([
            new Permission([
                'name' => AccessCardPolicy::ACCESS_CARD_LIST,
                'description' => "Voir la liste des cartes d'accès",
            ]),
            new Permission([
                'name' => AccessCardPolicy::ACCESS_CARD_CREATE,
                'description' => "Ajouter une carte d'accès",
            ]),
            new Permission([
                'name' => AccessCardPolicy::ACCESS_CARD_DELETE,
                'description' => "Supprimer une carte d'accès",
            ]),
            new Permission([
                'name' => AccessCardPolicy::ACCESS_CARD_TOPUP,
                'description' => "Recharger une carte d'accès",
            ]),
        ]);

        $menus = Permission::create([
            'name' => MenuPolicy::MENU_MANAGE,
            'description' => 'Toutes les permissions relatives à la gestion des menus'
        ]);

        $menus->children()->saveMany([
            new Permission([
                'name' => MenuPolicy::MENU_LIST,
                'description' => 'Voir la liste des menus',
            ]),
            new Permission([
                'name' => MenuPolicy::MENU_CREATE,
                'description' => 'Ajouter un menu'
            ]),
            new Permission([
                'name' => MenuPolicy::MENU_UPDATE,
                'description' => 'Modifier un menu'
            ]),
            new Permission([
                'name' => MenuPolicy::MENU_DELETE,
                'description' => 'Supprimer un menu'
            ])
        ]);

        $orders = Permission::create([
            'name' => OrderPolicy::ORDER_MANAGE,
            'description' => 'Toutes les permissions relatives à la gestion des commandes'
        ]);

        $orders->children()->saveMany([
            new Permission([
                'name' => OrderPolicy::ORDER_LIST,
                'description' => 'Voir la liste des commandes',
            ]),
            new Permission([
                'name' => OrderPolicy::ORDER_CREATE,
                'description' => 'Passer une commande'
            ]),
            new Permission([
                'name' => OrderPolicy::ORDER_UPDATE,
                'description' => 'Modifier les commandes'
            ]),
        ]);

        $departments = Permission::create([
            'name' => DepartmentPolicy::DEPARTMENT_MANAGE,
            'description' => 'Toutes les permissions relatives à la gestion des départements'
        ]);

        $departments->children()->saveMany([
            new Permission([
                'name' => DepartmentPolicy::DEPARTMENT_LIST,
                'description' => 'Voir la liste des départements',
            ]),
            new Permission([
                'name' => DepartmentPolicy::DEPARTMENT_CREATE,
                'description' => 'Ajouter un département'
            ]),
            new Permission([
                'name' => DepartmentPolicy::DEPARTMENT_UPDATE,
                'description' => 'Modifier un département'
            ]),
            new Permission([
                'name' => DepartmentPolicy::DEPARTMENT_DELETE,
                'description' => 'Supprimer un département'
            ])
        ]);

        $organizations = Permission::create([
            'name' => OrganizationPolicy::ORGANIZATION_MANAGE,
            'description' => 'Toutes les permissions relatives à la gestion des sociétés'
        ]);

        $organizations->children()->saveMany([
            new Permission([
                'name' => OrganizationPolicy::ORGANIZATION_LIST,
                'description' => 'Voir la liste des sociétés',
            ]),
            new Permission([
                'name' => OrganizationPolicy::ORGANIZATION_CREATE,
                'description' => 'Ajouter une société'
            ]),
            new Permission([
                'name' => OrganizationPolicy::ORGANIZATION_UPDATE,
                'description' => 'Modifier une société'
            ]),
            new Permission([
                'name' => OrganizationPolicy::ORGANIZATION_DELETE,
                'description' => 'Supprimer une société'
            ])
        ]);

        /**
         * Attach permissions to roles
         */
        Role::create([
            'id' => Role::ADMIN,
            'name' => 'Admin fonctionnel',
        ])->givePermissionTo([
            UserPolicy::USER_MANAGE,
            AccessCardPolicy::ACCESS_CARD_MANAGE,
            OrderPolicy::ORDER_MANAGE,
            OrganizationPolicy::ORGANIZATION_MANAGE,
            DepartmentPolicy::DEPARTMENT_MANAGE,
        ]);

        Role::create([
            'id' => Role::USER,
            'name' => 'Utilisateur',
        ])->givePermissionTo([
            OrderPolicy::ORDER_CREATE,
            OrderPolicy::ORDER_LIST,
        ]);

        Role::create([
            'id' => Role::ADMIN_RH,
            'name' => 'Administrateur Ressources Humaines',
        ])->givePermissionTo([
            UserPolicy::USER_LIST,
            OrderPolicy::ORDER_CREATE,
            OrderPolicy::ORDER_LIST,
            AccessCardPolicy::ACCESS_CARD_MANAGE,
        ]);

        Role::create([
            'id' => Role::ADMIN_LUNCHROOM,
            'name' => 'Responsable Cantine',
        ])->givePermissionTo([
            OrderPolicy::ORDER_MANAGE,
            MenuPolicy::MENU_MANAGE,
            AccessCardPolicy::ACCESS_CARD_MANAGE,
        ]);

        Role::create([
            'id' => Role::ACCOUNTANT,
            'name' => 'Comptable',
        ])->givePermissionTo([
            OrderPolicy::ORDER_MANAGE,
            MenuPolicy::MENU_MANAGE,
        ]);

        Role::create([
            'id' => Role::OPERATOR_LUNCHROOM,
            'name' => 'Agent Cantine',
        ])->givePermissionTo([
            OrderPolicy::ORDER_MANAGE,
            MenuPolicy::MENU_MANAGE,
        ]);
    }
}
