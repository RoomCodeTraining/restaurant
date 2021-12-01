<?php

namespace App\Actions\PaymentMethods;

use Illuminate\Support\Facades\DB;
use App\Models\PaymentMethod;

class UpdatePaymentMethodAction
{
    public function execute(PaymentMethod $paymentMethod, array $input): PaymentMethod
    {
        DB::beginTransaction();

        $paymentMethod->update([
            'name' => $input['name'],
            'description' => $input['description'] ?? null,
        ]);


        DB::commit();

        return $paymentMethod;
    }
}
