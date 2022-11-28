<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;


class DateWisePrintFactoryReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue, WithEvents
{
    use Exportable;

    private $result_data;
    private $poCount;
    private $colorCount;

    public function __construct($data)
    {
        $this->result_data = $data;
        $this->poCount = $data['po_count'];
        $this->colorCount = $data['color_count'];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Date Wise Print Report';
    }


    /**
     * @return View
     */
    public function view(): View
    {
        $data = $this->result_data;
        return view('printembrdroplets::reports.downloads.excel.date-wise-delivery-report-download', $data);
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $poCount = $this->poCount;
                $colorCount = $this->colorCount;
                $highestRow = $event->sheet->getDelegate()->getHighestRow();
                // PO Table Head
                $event->sheet->getDelegate()->getStyle('A1:G4')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A1:G4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                // PO Table Body Left Content
                $event->sheet->getDelegate()->getStyle('A5:C' . ($poCount + 4))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                // PO Table Body Right Content
                $event->sheet->getDelegate()->getStyle('D5:G' . ($poCount + 4))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                // PO Table Footer
                $event->sheet->getDelegate()->getStyle('A' . ($poCount + 5) . ':G' . ($poCount + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A' . ($poCount + 5) . ':G' . ($poCount + 5))->getFont()->setBold(true);

                // Color table Head
                $event->sheet->getDelegate()->getStyle('A' . ($poCount + 7) . ':H' . ($poCount + 8))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A' . ($poCount + 7) . ':H' . ($poCount + 8))->getFont()->setBold(true);
                // Color table Body Left Content
                $event->sheet->getDelegate()->getStyle('A' . ($poCount + 8) . ':D' . ($highestRow - 6))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                // Color table Body Right Content
                $event->sheet->getDelegate()->getStyle('E' . ($poCount + 8) . ':H' . ($highestRow - 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                // Color Table Footer
                $event->sheet->getDelegate()->getStyle('A' . ($highestRow - 5) . ':H' . ($highestRow - 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A' . ($highestRow - 5) . ':H' . ($highestRow - 5))->getFont()->setBold(true);

                // Factory table Head
                $event->sheet->getDelegate()->getStyle('A' . ($highestRow - 4) . ':F' . ($highestRow - 2))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A' . ($highestRow - 4) . ':F' . ($highestRow - 2))->getFont()->setBold(true);
                // Factory table Footer
                $event->sheet->getDelegate()->getStyle('A' . ($highestRow) . ':F' . ($highestRow))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A' . ($highestRow) . ':F' . ($highestRow))->getFont()->setBold(true);


            }];
    }
}