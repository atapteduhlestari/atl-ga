<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;

class RenewalExportSummaryView implements
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
        return view('export.summary.renewal', compact('data'));
    }

    public function properties(): array
    {
        return [
            'creator'        => 'Staff IT',
            'lastModifiedBy' => 'Administrator',
            'title'          => 'Renewal Summary Report',
            'description'    => 'Renewal Summary Report',
            'subject'        => 'Renewal Summary Report',
            'category'       => 'Report',
            'company'        => 'PT. ATAP TEDUH LESTARI',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $lastColumn = $event->sheet->getHighestColumn();
                $column = 9;
                $totalData = count($this->data['transactions']) + $column;

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
                $rowDataCellRange = 'A8:' . $lastColumn . $totalData;
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


    // public function drawings()
    // {
    //     $drawing = new Drawing();
    //     $drawing->setName('Logo');
    //     $drawing->setDescription('ATL Logo');
    //     $drawing->setPath(public_path('/assets/img/logo.png'));
    //     $drawing->setHeight(50);
    //     $drawing->setCoordinates('A1');
    //     $drawing->setOffsetX(150);
    //     $drawing->setOffsetY(300);
    //     $drawing->getShadow()->setVisible(true);
    //     $drawing->getShadow()->setDirection(30);

    //     return $drawing;
    // }
}
