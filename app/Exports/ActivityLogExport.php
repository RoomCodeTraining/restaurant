<?php

namespace App\Exports;

use App\Support\ActivityHelper;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Spatie\Activitylog\Models\Activity;
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

class ActivityLogExport implements FromCollection, WithTitle, WithMapping, WithHeadings, WithStyles
{

  use Exportable;


  public $date;

  public function __construct($date)
  {
    $this->date = $date;
  }
  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
        return Activity::query()->whereDate('created_at', $this->date)->orderByDesc('created_at')->get();
  }

  public function headings(): array
  {
    return [
      'DATE',
      'HEURE',
      'MENER PAR',
      'ACTION',
    ];
  }

  public function map($activity): array
  {

    return [
      $activity->created_at->format('d/m/Y'),
      $activity->created_at->format('H:i'),
      ActivityHelper::createdBy($activity->causer_id),
      $activity->event,
    ];
  }

  public function title() : string
  {
    return "Liste des activiés du $this->date->format('d-m-Y') ";
  }

  public function styles(Worksheet $sheet)
  {
    $sheet->setAutoFilter('A1:D' . $sheet->getHighestRow());

    $sheet->getStyle('A1:D1')->applyFromArray([
      'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true, 'size' => 11],
      'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '538ED5']]
    ]);

    $sheet->getRowDimension(1)->setRowHeight(15);

    $sheet->getStyle('A2:D' . $sheet->getHighestRow())->applyFromArray([
      'borders' => [
        'allBorders' => [
          'borderStyle' => Border::BORDER_THIN,
          'color' => ['rgb' => '000000'],
        ],
      ],
    ]);
  }
}
