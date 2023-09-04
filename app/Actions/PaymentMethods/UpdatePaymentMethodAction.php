<?php

namespace App\Actions\PaymentMethods;

use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;

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
