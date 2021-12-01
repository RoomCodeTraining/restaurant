<?php

namespace App\Actions\PaymentMethods;

use App\Models\PaymentMethod;
use Illuminate\Validation\ValidationException;

final class DeletePaymentMethodAction
{
    public function execute(PaymentMethod $paymentMethod): PaymentMethod
    {
        if (null !== $paymentMethod->deleted_at) {
            throw_if(null !== $paymentMethod->deleted_at, ValidationException::withMessages([
                'delete_menu' => 'Cette methpode est deja supprimée.',
            ]));
        }

        $paymentMethod->delete();

        // dishDeleted::dispatch($dish);

        return $paymentMethod;
    }
}
