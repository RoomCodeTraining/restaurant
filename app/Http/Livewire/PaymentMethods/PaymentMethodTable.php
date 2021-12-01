<?php

namespace App\Http\Livewire\PaymentMethods;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\PaymentMethod;
use App\Actions\PaymentMethods\DeletePaymentMethodAction;

class PaymentMethodTable extends DataTableComponent
{

    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public string $defaultSortColumn = 'created_at';
    public string $defaultSortDirection = 'desc';

    public $PaymentMethodIdBeingDeleted;
    public $confirmingPaymentMethodDeletion = false;

    public function columns(): array
    {
        return [
            Column::make('Nom', 'name')->searchable(),
            Column::make('Description', 'description')->searchable(),
            Column::make('Date de création', 'created_at')->format(fn ($row) => $row->format('d/m/Y'))->sortable(),
            Column::make('Actions')->format(fn ($value, $column, paymentMethod $paymentMethod) => view('livewire.payment-methods.table-actions')->with([
                'paymentMethod' => $paymentMethod,
            ])),
        ];
    }


    public function modalsView(): string
    {
        return 'livewire.payment-methods.modals';
    }

    public function confirmPaymentMethodDeletion($paymentMethodId)
    {
        $this->paymentMethodIdBeingDeleted = $paymentMethodId;
        $this->confirmingPaymentMethodDeletion = true;
    }

    public function deletePaymentMethod(DeletePaymentMethodAction $action){

        $paymentMethod = PaymentMethod::find($this->paymentMethodIdBeingDeleted);

        $action->execute($paymentMethod);

        $this->confirmingPaymentMethodDeletion = false;
        $this->paymentMethodIdBeingDeleted = null;

        session()->flash('success', "La methode de paiement a été supprimée avec succès !");

        return redirect()->route('paymentMethods.index');
    }

    public function query(): Builder
    {
        return PaymentMethod::query();
    }
}
