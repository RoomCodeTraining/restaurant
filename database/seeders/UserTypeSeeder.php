<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use App\Models\UserType;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserType::create(['name' => 'Agent CIPREL', 'payment_method_id' => PaymentMethod::firstWhere('name', 'Postpaid')->id]);
        UserType::create(['name' => 'Agent Non CIPREL', 'payment_method_id' => PaymentMethod::firstWhere('name', 'Cash')->id, 'auto_identifier' => true]);
        UserType::create(['name' => 'Invité', 'payment_method_id' => PaymentMethod::firstWhere('name', 'Subvention')->id, 'auto_identifier' => true]);
        UserType::create(['name' => 'Stagiaire', 'payment_method_id' => PaymentMethod::firstWhere('name', 'Subvention')->id, 'auto_identifier' => true]);
        UserType::create(['name' => 'Prestataire', 'payment_method_id' => PaymentMethod::firstWhere('name', 'Subvention')->id, 'auto_identifier' => true]);
    }
}
