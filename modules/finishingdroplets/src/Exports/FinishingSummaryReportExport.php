<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Exports;

use App\CustomExcelHeaderFooter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class FinishingSummaryReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue, WithEvents
{
    use Exportable, CustomExcelHeaderFooter;

    private $result_data;

    public function __construct($data)
    {
        $this->result_data = $data;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Finishing Report';
    }


    /**
     * @return View
     */
    public function view(): View
    {
        $data = $this->result_data;
        return view('finishingdroplets::reports.downloads.excels.finishing_summary_report_download', $data);
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function (BeforeExport $event) {
                $event->writer->setCreator('Skylark Soft Limited');
            },
            AfterSheet::class => function (AfterSheet $event) {
                $cell_array = range('A', 'W');
                $head_array_number = [1, 2, 3];
                $highestRowNumber = $event->sheet->getDelegate()->getHighestRow();
                $footer_array_number = [$highestRowNumber];
                $this->excelHeaderFooter($event, $cell_array, $head_array_number, $footer_array_number);

                // Custom Style
                $event->sheet->getDelegate()->getStyle($cell_array[0].$head_array_number[0].':'.$cell_array[4].($footer_array_number[count($footer_array_number) - 1] - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle($cell_array[0].$head_array_number[0].':'.$cell_array[4].($footer_array_number[count($footer_array_number) - 1] - 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            }
        ];
    }
}