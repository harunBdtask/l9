<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Exports;


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

class CuttingWiseCuttingReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue, WithEvents
{
	use Exportable, CustomExcelHeaderFooter;

	private $cutting_report;

	public function __construct($cutting_report)
	{
		$this->cutting_report = $cutting_report;
	}

	/**
	 * @return string
	 */
	public function title(): string
	{
		return 'Cutting Wise Cutting Report';
	}


	/**
	 * @return View
	 */
	public function view(): View
	{
		$data = $this->cutting_report;
		return view('cuttingdroplets::reports.includes.cutting-no-wise-cutting-report-table-inc-download', $data);
	}

	/**
	 * @return array
	 */
	public function registerEvents(): array
	{
		return [
			BeforeExport::class => function (BeforeExport $event) {
				$event->writer->getProperties()->setCreator('Skylark Soft Limited');
			},
			AfterSheet::class => function (AfterSheet $event) {

				$cell_array = range('A', 'D');
				$head_array_number = [1, 2, 3, 4];
				$highestRowNumber = $event->sheet->getDelegate()->getHighestRow();
				$footer_array_number = [$highestRowNumber];
				$this->excelHeaderFooter($event, $cell_array, $head_array_number, $footer_array_number, $footer_exist = 1);

				// Custom Style
				$event->sheet->getDelegate()->getStyle($cell_array[0] . $head_array_number[0] . ':' . $cell_array[0] . ($footer_array_number[count($footer_array_number) - 1] - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
				$event->sheet->getDelegate()->getStyle($cell_array[0] . $head_array_number[0] . ':' . $cell_array[0] . ($footer_array_number[count($footer_array_number) - 1] - 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
				$event->sheet->getDelegate()->getStyle($cell_array[1] . $head_array_number[0] . ':' . $cell_array[3] . ($footer_array_number[count($footer_array_number) - 1] - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
				$event->sheet->getDelegate()->getStyle($cell_array[1] . $head_array_number[0] . ':' . $cell_array[3] . ($footer_array_number[count($footer_array_number) - 1] - 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
				$event->sheet->mergeCells('A' . $head_array_number[0] . ':D' . $head_array_number[0]);
				$event->sheet->mergeCells('A' . $head_array_number[1] . ':D' . $head_array_number[1]);
				$event->sheet->getStyle('A' . $head_array_number[2] . ':D' . $head_array_number[2])->getAlignment()->setWrapText(true);
			},
		];
	}
}