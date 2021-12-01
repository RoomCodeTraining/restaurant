<?php

namespace App\Actions\PaymentMethods;

use Illuminate\Support\Facades\DB;
use App\Models\PaymentMethod;

class CreatePaymentMethodAction
{
    public function execute(array $input): PaymentMethod
    {
        DB::beginTransaction();

        $paymentMethod = PaymentMethod::create([
            'name' => $input['name'],
            'description' => $input['description'] ?? null,
        ]);


        DB::commit();

        return $paymentMethod;
    }
}
