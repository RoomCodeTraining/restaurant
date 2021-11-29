<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Policies\AccessCardPolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\EmployeeStatusPolicy;
use App\Policies\MenuPolicy;
use App\Policies\OrderPolicy;
use App\Policies\OrganizationPolicy;
use App\Policies\PaymentMethodPolicy;
use App\Policies\UserPolicy;
use App\Policies\UserTypePolicy;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\PermissionRegistrar;

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
        // Reset cached roles and permissions
        Artisan::call('cache:clear');
        resolve(PermissionRegistrar::class)->forgetCachedPermissions();

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
                'description' => 'Modifier une commande'
            ]),
            new Permission([
                'name' => OrderPolicy::ORDER_DELETE,
                'description' => 'Annuler une commande'
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

        $userTypes = Permission::create([
            'name' => UserTypePolicy::USER_TYPE_MANAGE,
            'description' => 'Toutes les permissions relatives à la gestion des types d\'utilisateur'
        ]);

        $userTypes->children()->saveMany([
            new Permission([
                'name' => UserTypePolicy::USER_TYPE_LIST,
                'description' => "Voir la liste des types d'utilisateur",
            ]),
            new Permission([
                'name' => UserTypePolicy::USER_TYPE_CREATE,
                'description' => 'Ajouter un type d\'utilisateur'
            ]),
            new Permission([
                'name' => UserTypePolicy::USER_TYPE_UPDATE,
                'description' => 'Modifier un type d\'utilisateur'
            ]),
            new Permission([
                'name' => UserTypePolicy::USER_TYPE_DELETE,
                'description' => 'Supprimer un type d\'utilisateur'
            ])
        ]);

        $employeeStatuses = Permission::create([
            'name' => EmployeeStatusPolicy::EMPLOYEE_STATUS_MANAGE,
            'description' => 'Toutes les permissions relatives à la gestion des catégories d\'employés'
        ]);

        $employeeStatuses->children()->saveMany([
            new Permission([
                'name' => EmployeeStatusPolicy::EMPLOYEE_STATUS_LIST,
                'description' => 'Voir la liste des catégories d\'employés',
            ]),
            new Permission([
                'name' => EmployeeStatusPolicy::EMPLOYEE_STATUS_CREATE,
                'description' => 'Ajouter un catégorie d\'employés'
            ]),
            new Permission([
                'name' => EmployeeStatusPolicy::EMPLOYEE_STATUS_UPDATE,
                'description' => 'Modifier un catégorie d\'employés'
            ]),
            new Permission([
                'name' => EmployeeStatusPolicy::EMPLOYEE_STATUS_DELETE,
                'description' => 'Supprimer un catégorie d\'employés'
            ])
        ]);

        $paymentMethods = Permission::create([
            'name' => PaymentMethodPolicy::PAYMENT_METHOD_MANAGE,
            'description' => 'Toutes les permissions relatives à la gestion des méthodes de paiement'
        ]);

        $paymentMethods->children()->saveMany([
            new Permission([
                'name' => PaymentMethodPolicy::PAYMENT_METHOD_LIST,
                'description' => 'Voir la liste des méthodes de paiement',
            ]),
            new Permission([
                'name' => PaymentMethodPolicy::PAYMENT_METHOD_CREATE,
                'description' => 'Ajouter une méthode de paiement'
            ]),
            new Permission([
                'name' => PaymentMethodPolicy::PAYMENT_METHOD_UPDATE,
                'description' => 'Modifier une méthode de paiement'
            ]),
            new Permission([
                'name' => PaymentMethodPolicy::PAYMENT_METHOD_DELETE,
                'description' => 'Supprimer une méthode de paiement'
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
            OrderPolicy::ORDER_MANAGE,
            OrganizationPolicy::ORGANIZATION_MANAGE,
            DepartmentPolicy::DEPARTMENT_MANAGE,
            PaymentMethodPolicy::PAYMENT_METHOD_MANAGE,
            EmployeeStatusPolicy::EMPLOYEE_STATUS_MANAGE,
            UserTypePolicy::USER_TYPE_MANAGE,
        ]);

        Role::create([
            'id' => Role::USER,
            'name' => 'Utilisateur',
        ])->givePermissionTo([
            OrderPolicy::ORDER_MANAGE,
        ]);

        Role::create([
            'id' => Role::ADMIN_RH,
            'name' => 'Administrateur Ressources Humaines',
        ])->givePermissionTo([
            UserPolicy::USER_LIST,
            OrderPolicy::ORDER_MANAGE,
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
        ]);

        Role::create([
            'id' => Role::OPERATOR_LUNCHROOM,
            'name' => 'Agent Cantine',
        ])->givePermissionTo([
            OrderPolicy::ORDER_MANAGE,
        ]);
    }
}
