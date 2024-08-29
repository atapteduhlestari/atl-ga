<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;

class RenewalExportDetailView implements
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
        return view('export.renewal', compact('data'));
    }

    public function properties(): array
    {
        return [
            'creator'        => 'Staff IT',
            'lastModifiedBy' => 'Administrator',
            'title'          => 'Renewal Detail Report',
            'description'    => 'Renewal Detail Report',
            'subject'        => 'Renewal Detail Report',
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

                $styleArray = [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '#000000'],
                        ],
                    ],
                ];

                $sheet = $event->sheet;
                $rowDataCellRange = 'A8:' . $lastColumn . $totalData + 8;

                $sheet->getStyle('A8:L8')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('A9D08E');
                $sheet->getDelegate()->getStyle('A8:L8')->getFont()->setBold(true);
                $sheet->getDelegate()->getStyle($rowDataCellRange)->applyFromArray($styleArray);
            },
        ];
    }
}
