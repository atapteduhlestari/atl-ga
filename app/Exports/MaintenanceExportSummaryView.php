<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;
use PhpOffice\PhpSpreadsheet\Style\Alignment;



class MaintenanceExportSummaryView implements
    FromView,
    WithProperties,
    WithEvents,
    ShouldAutoSize
{

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        $data = $this->data;
        return view('export.summary.maintenance', compact('data'));
    }

    public function properties(): array
    {
        return [
            'creator'        => 'Staff IT',
            'lastModifiedBy' => 'Administrator',
            'title'          => 'maintenance Summary Report',
            'description'    => 'maintenance Summary Report',
            'subject'        => 'maintenance Summary Report',
            'category'       => 'Report',
            'company'        => 'PT. ATAP TEDUH LESTARI',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $lastColumn = $event->sheet->getHighestColumn();
                $totalData = count($this->data['transactions']);
                $columnData = 9;

                $styleArray = [
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '#000000'],
                        ],
                    ],
                ];
                $rowDataCellRange = 'A8:' . $lastColumn . $totalData + $columnData;
                $sheet = $event->sheet;

                $sheet->getStyle('A8:D8')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('A9D08E');
                $sheet->getStyle('A8:D8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A8:D8')->getFont()->setBold(true);

                $sheet->getDelegate()->getStyle($rowDataCellRange)->applyFromArray($styleArray);
            },
        ];
    }
}
