<?php

namespace App\Exports;

use App\Support\DateTimeHelper;
use App\Models\ReloadAccessCardHistory;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;


class ReloadAccessCardHistoryExport implements FromCollection, WithHeadings, WithTitle, WithMapping, WithStyles
{

  use Exportable;

  public $period, $quota_type;

  public function __construct($period, ?string $quota_type)
  {
    $this->period = $period;
    $this->quota_type = $quota_type;
  }
  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    return ReloadAccessCardHistory::query()
        ->whereHas('accessCard', function ($query) {
          $query->whereHas('user', function ($query) {
            $query->where('deleted_at', null);
          });
        })
      ->when($this->quota_type, fn ($query) => $query->where('quota_type', $this->quota_type))
      ->when($this->period, fn ($query) => $query->whereBetween('created_at', DateTimeHelper::inThePeriod($this->period)))
      ->orderBy('created_at', 'desc')->get();
  }

  public function title(): string
  {
    return 'Historique de rechargement';
  }

  public function headings(): array
  {
    return [
      'Date',
      'Matricule',
      'Utilisateur',
      'Type de quota',
      'Quota'
    ];
  }

  public function map($history): array
  {
    return [
      \Carbon\Carbon::parse($history->created_at)->format('d/m/Y'),
      $history->accessCard->user->identifier,
      $history->accessCard->user->full_name,
      $history->quota_type == 'lunch' ? 'Dejeuner' : 'petit dejeuner',
      $history->quota
    ];
  }


  public function styles(Worksheet $sheet)
  {
      $sheet->setAutoFilter('A1:E' . $sheet->getHighestRow());

      $sheet->getStyle('A1:E1')->applyFromArray([
          'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true, 'size' => 13],
          'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '538ED5']]
      ]);

      $sheet->getRowDimension(1)->setRowHeight(20);

      $sheet->getStyle('A2:E' . $sheet->getHighestRow())->applyFromArray([
          'borders' => [
              'allBorders' => [
                  'borderStyle' => Border::BORDER_THIN,
                  'color' => ['rgb' => '000000'],
              ],
          ],
      ]);
  }


}
