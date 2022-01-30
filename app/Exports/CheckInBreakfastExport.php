<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Order;
use App\Support\BillingHelper;
use App\States\Order\Completed;
use App\Support\DateTimeHelper;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CheckInBreakfastExport implements FromCollection, WithHeadings, WithTitle, WithMapping, WithStyles
{

    use Exportable;
    protected $period;


    public function __construct($period)
    {
        $this->period = $period;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
      return  Order::query()->whereState('state', Completed::class)->withoutGlobalScope('lunch')->with('user', 'menu')->whereBetween('created_at', DateTimeHelper::inThePeriod($this->period))->get();
    }


    public function title() : string {
      return 'Pointage pet dej'.Carbon::parse($this->period)->format('d/m/y');
    }




    public function headings(): array
    {
        return [
            "Nom",
            "Prénom",
            "Email",
            "Contact",
            "Rôle",
            "Société",
            "Département",
            "Type de collaborateur",
            "Catégorie professionnelle",
            "Date",
            "Méthode de paiement",
            "A payer",
            "Subvention",
        ];
    }

    public function map($row) : Array
    {
    
  
      $date = $row->created_at;
      $userBill = BillingHelper::getUserBill($row->user, $row);

  
      $contribution =  $userBill['contribution']['breakfast'];
      $subvention = $userBill['subvention']['breakfast'];
      $order_type = 'petit déjeuner';
      return [
          $row->user->last_name,
          $row->user->first_name,
          $row->user->email,
          $row->user->contact,
          $row->user->role->name,
          $row->user->organization->name,
          $row->user->department->name,
          $row->user->userType->name,
          $row->user->employeeStatus->name,
          $date->format('d/m/Y'),
          $row->user->accessCard->paymentMethod->name,
          0,
          0,
      ];
    }


    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:O' . $sheet->getHighestRow());

        $sheet->getStyle('A1:O1')->applyFromArray([
            'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true, 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '538ED5']]
        ]);

        $sheet->getRowDimension(1)->setRowHeight(15);

        $sheet->getStyle('A2:O' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
    }
}
