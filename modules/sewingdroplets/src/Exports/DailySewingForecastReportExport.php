<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Exports;

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

class DailySewingForecastReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue, WithEvents
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
        return 'Daily Sewing Forecast Report';
    }

    public function registerEvents(): array
    {
        return [
            BeforeExport::class  => function(BeforeExport $event) {
                $event->writer->setCreator('Skylark Soft Limited');
            },
            AfterSheet::class => function (AfterSheet $event) {
                $cell_array = range('A','S');
                $head_array_number = [1, 2, 3];
                $highestRowNumber = $event->sheet->getDelegate()->getHighestRow();
                $footer_array_number = [$highestRowNumber + 1];
                $this->excelHeaderFooter($event, $cell_array, $head_array_number, $footer_array_number, 0);
                // Table Body Customize                
                $event->sheet->getDelegate()->getStyle($cell_array[0].($head_array_number[count($head_array_number)-1] + 1).':'.$cell_array[5].($highestRowNumber - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle($cell_array[0].($head_array_number[count($head_array_number)-1] + 1).':'.$cell_array[5].($highestRowNumber - 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                $event->sheet->getDelegate()->getStyle($cell_array[6].($head_array_number[count($head_array_number)-1] + 1).':'.$cell_array[count($cell_array) - 1].($highestRowNumber - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            }
        ];
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $data = $this->result_data;
        return view('sewingdroplets::reports.downloads.excels.daily_sewing_forecast_report_excel', $data);
    }

}