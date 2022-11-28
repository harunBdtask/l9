<?php


namespace SkylarkSoft\GoRMG\Commercial\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Sheet;


class LCRequestExport implements FromView, WithTitle, WithEvents
{
    use Exportable;

    private $resultData;

    public function __construct($data)
    {
        $this->resultData = $data;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'LC Request';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $data = $this->resultData;
        return view('commercial::lc-request.excel', $data);
    }

    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function(AfterSheet $event) {
                $count = optional($this->resultData->details)->count();
                //$event->sheet->getDelegate()->mergeCells('A1:K13');

                $event->sheet->getDelegate()->getStyle('A1:K12')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE
                        ],
                    ],
                ]);

                $event->sheet->getDelegate()->getStyle('A13:K' . (13 + $count))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                        ],
                    ],
                ]);

                $event->sheet->getDelegate()->getStyle('A13:K13')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ],
                ]);

            },
        ];
    }
}
