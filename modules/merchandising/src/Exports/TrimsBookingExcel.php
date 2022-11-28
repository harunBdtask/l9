<?php

namespace SkylarkSoft\GoRMG\Merchandising\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TrimsBookingExcel implements WithTitle, FromView, ShouldAutoSize, WithEvents
{
    use Exportable;

    const WITH_PRICE = 1, WITHOUT_PRICE = 2;

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Trims Booking';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        if (request()->get('pdfNumber') == self::WITH_PRICE) {
            if (request()->get('type') == 'v6') {
                return view('merchandising::booking.reports.excel.view-body-v6-excel-with-price', $this->data);
            } else {
                return view('merchandising::booking.reports.excel.view-body-v4-excel-with-price', $this->data);
            }
        } else {
            if (request()->get('type') == 'v6') {
                return view('merchandising::booking.reports.excel.view-body-v6-excel-without-price', $this->data);
            } elseif (request()->query('type') == 'v9') {
                return view('merchandising::booking.reports.excel.view-body-v9-excel', $this->data);
            } else {
                return view('merchandising::booking.reports.excel.view-body-v4-excel-without-price', $this->data);
            }
        }
    }

    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
            $sheet = $event->getSheet();
            $sheet->autoSize(true);

            $event->sheet->getDelegate()->getStyle('A1:J1')->getFont()->setSize('20')->setBold(true);
            $event->sheet->getDelegate()->getStyle('A1:J1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $event->sheet->getDelegate()->getStyle('A2:J2')->getFont()->setSize('15')->setBold(true);
            $event->sheet->getDelegate()->getStyle('A2:J2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }];
    }
}
