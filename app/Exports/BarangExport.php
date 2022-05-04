<?php

namespace App\Exports;

use App\Models\BoxType;
use App\Models\Customer;
use App\Models\CustomerDiscountCategory;
use App\Models\ProductInCatalog;
use App\Models\Rate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class BarangExport implements FromView
,ShouldAutoSize
,WithEvents
// WithTitle
// WithMultipleSheets
// WithColumnFormatting
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($data)
    {
        $this->data = $data;
        // $this->customer = $customer;
        // $this->currency = $currency;
    }

    public function title(): string
    {
        return 'Laporan Stock Opname';
    }

    // public function registerEvents(): array
    // {
    //     return [
    //         AfterSheet::class => function (AfterSheet $event) {

    //             $event->sheet->getDelegate()->freezePane('D4');
    //             $event->sheet->getDelegate()->getStyle('A1:M3')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    //         },
    //     ];
    // }

    // public function columnFormats(): array
    // {
    //     return [
    //         'E' => NumberFormat::FORMAT_NUMBER_00,
    //         'H' => NumberFormat::FORMAT_NUMBER_00,
    //         'F' => NumberFormat::FORMAT_NUMBER_00,
    //         'I' => NumberFormat::FORMAT_NUMBER_00,
    //     ];
    // }

    public function registerEvents(): array
    {

        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $event->sheet->getDelegate()->freezePane('D3');

                $event->sheet->getStyle('A1:'. $event->sheet->getActiveCell())->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ]
                ]);
            },
        ];
    }

    public function view(): View
    {
        return view('export.laporan-stock-opname', $this->data);
    }
}
