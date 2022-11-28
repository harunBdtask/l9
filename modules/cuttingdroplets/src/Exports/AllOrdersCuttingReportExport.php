<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Exports;

use App\CustomExcelHeaderFooter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;

class AllOrdersCuttingReportExport implements WithTitle, ShouldAutoSize, WithMapping, WithHeadings, FromQuery, WithEvents, ShouldQueue, WithCustomChunkSize
{
	use Exportable, CustomExcelHeaderFooter;

	private $grand_po_qty;
	private $grand_todays_cutting;
	private $grand_total_cutting;
	private $grand_left_extra_qty;

	private $grand_print_sent;
	private $grand_print_recv;
	private $grand_embr_sent;
	private $grand_embr_recv;
	private $grand_input;
	private $grand_output;
	private $order_id, $from_date, $to_date;

	public function __construct($order_id, $from_date, $to_date)
	{
		$this->order_id = $order_id;
		$this->from_date = $from_date;
		$this->to_date = $to_date;
		$this->grand_po_qty = 0;
		$this->grand_todays_cutting = 0;
		$this->grand_total_cutting = 0;
		$this->grand_left_extra_qty = 0;
		$this->grand_print_sent = 0;
		$this->grand_print_recv = 0;
		$this->grand_embr_sent = 0;
		$this->grand_embr_recv = 0;
		$this->grand_input = 0;
		$this->grand_output = 0;
	}

	/**
	 * @return string
	 */
	public function title(): string
	{
		return 'All Orders Cutting Report';
	}


	/**
	 * @return Builder
	 */
	public function query()
	{
		$order_id = $this->order_id ?? '';
		$from_date = $this->from_date ?? '';
		$to_date = $this->to_date ?? '';

		$reportData = TotalProductionReport::query()
			->with([
				'buyer:id,name',
				'order:id,style_name',
				'purchaseOrder:id,po_no,po_quantity',
				'color:id,name',
			])
			->selectRaw('
            buyer_id, 
            order_id, 
            purchase_order_id, 
            color_id, 
            SUM(todays_cutting) as todays_cutting_sum, 
            SUM(todays_cutting_rejection) as todays_cutting_rejection_sum, 
            SUM(total_cutting) as total_cutting_sum, 
            SUM(total_cutting_rejection) as total_cutting_rejection_sum,
            SUM(total_sent) as total_sent_sum,
            SUM(total_embroidary_sent) as total_embroidary_sent_sum,
            SUM(total_received) as total_received_sum,
            SUM(total_embroidary_received) as total_embroidary_received_sum,
            SUM(total_input) as total_input_sum,
            SUM(total_sewing_output) as total_output_sum
            ')
			->when($order_id != '', function ($query) use ($order_id) {
				return $query->where('order_id', $order_id);
			})
			->when($order_id == '' && $from_date == '' && $to_date == '', function ($query) {
				return $query->whereDate('created_at', '>=', now()->subDays(184)->toDateString());
			})
			->when($order_id == '' && $from_date != '' && $to_date != '', function ($query) use ($from_date, $to_date) {
				return $query->whereDate('created_at', '>=', $from_date)
					->whereDate('created_at', '<=', $to_date);
			})
			->groupBy('buyer_id', 'order_id', 'purchase_order_id', 'color_id')
			->orderBy('buyer_id', 'desc');


		return $reportData;
	}

	/**
	 * @param mixed $report
	 * @return array
	 * @internal param mixed $row
	 *
	 */
	public function map($report): array
	{
		$buyer_name = $report->buyer->name ?? 'Buyer';
        $order = $report->order->style_name ?? 'Booking No';
		$purchase_order_no = $report->purchaseOrder->po_no ?? '';
		$color = $report->color->name ?? '';
		$purchase_order_id = $report->purchase_order_id;
		$color_id = $report->color_id;
		$color_wise_po_qty = \SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail::getColorWisePoQuantity($purchase_order_id, $color_id);

		/*$color_wise_po_qty = $report->purchaseOrder->po_quantity ?? 0;*/
		$todaysCutting = $report->todays_cutting_sum ?? 0;
		$totalCutting = $report->total_cutting_sum ?? 0;

		$xtra = ($color_wise_po_qty > 0) ? ((($totalCutting - $color_wise_po_qty) * 100) / $color_wise_po_qty) : 0;
		$xtra = $xtra > 0 ? $xtra : 0;
		$leftQty = $color_wise_po_qty - $report->total_cutting_sum;

		$this->grand_po_qty += $color_wise_po_qty ?? 0;
		$this->grand_todays_cutting += $report->todays_cutting_sum ?? 0;
		$this->grand_total_cutting += $report->total_cutting_sum ?? 0;
		$this->grand_left_extra_qty += $leftQty;
		$this->grand_print_sent += $report->total_sent_sum;
		$this->grand_print_recv += $report->total_received_sum;
		$this->grand_embr_sent += $report->total_embroidary_sent_sum;
		$this->grand_embr_recv += $report->total_embroidary_received_sum;
		$this->grand_input += $report->total_input_sum;
		$this->grand_output += $report->total_output_sum;

		return [
			$buyer_name ?? '',
			$order ?? '',
			$purchase_order_no ?? '',
			$color ?? '',
			$color_wise_po_qty ?? 0,
			$todaysCutting ?? 0,
			$totalCutting ?? 0,
			$leftQty ?? '',
			number_format($xtra, 2) . '%',
			$report->total_sent_sum,
			$report->total_received_sum,
			$report->total_embroidary_sent_sum,
			$report->total_embroidary_received_sum,
			$report->total_input_sum,
			$report->total_output_sum
		];
	}

	/**
	 * @return array
	 */
	public function headings(): array
	{
		return [
			'Buyer',
			'Style',
			'Purchase Order',
			'Color',
			'Color Wise PO Qty',
			'Today\'s Cutting',
			'Total Cutting',
			'Left/Extra Qty',
			'Extra Cutting (%)',
			'T. Print Sent',
			'T. Print Rcv.',
			'T. Embr. Sent',
			'T. Embr. Rcv.',
			'T. Input',
			'T. Output',
		];
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
			BeforeSheet::class => function (BeforeSheet $event) {
				$event->sheet->append([
					[
						'All Order\'s Cutting Report'
					],
					[
						sessionFactoryName(),
					]
				]);
			},
			AfterSheet::class => function (AfterSheet $event) {
				$event->sheet->append([
					'Total',
					'',
					'',
					'',
					$this->grand_po_qty,
					$this->grand_todays_cutting,
					$this->grand_total_cutting,
					$this->grand_left_extra_qty,
					'',
					$this->grand_print_sent,
					$this->grand_print_recv,
					$this->grand_embr_sent,
					$this->grand_embr_recv,
					$this->grand_input,
					$this->grand_output
				]);

				$cell_array = range('A', 'O');
				$head_array_number = [1, 2, 3];
				$highestRowNumber = $event->sheet->getDelegate()->getHighestRow();
				$footer_array_number = [$highestRowNumber];
				$this->excelHeaderFooter($event, $cell_array, $head_array_number, $footer_array_number, $footer_exist = 1);

				// Custom Style
				$event->sheet->getDelegate()->getStyle($cell_array[0] . $head_array_number[0] . ':' . $cell_array[3] . ($footer_array_number[count($footer_array_number) - 1] - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
				$event->sheet->getDelegate()->getStyle($cell_array[0] . $head_array_number[0] . ':' . $cell_array[3] . ($footer_array_number[count($footer_array_number) - 1] - 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
				$event->sheet->getDelegate()->getStyle($cell_array[10] . $head_array_number[0] . ':' . $cell_array[11] . ($footer_array_number[count($footer_array_number) - 1] - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
				$event->sheet->getDelegate()->getStyle($cell_array[10] . $head_array_number[0] . ':' . $cell_array[11] . ($footer_array_number[count($footer_array_number) - 1] - 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
				$event->sheet->mergeCells('A' . $head_array_number[0] . ':O' . $head_array_number[0]);
				$event->sheet->mergeCells('A' . $head_array_number[1] . ':O' . $head_array_number[1]);
				$event->sheet->mergeCells('A' . $highestRowNumber . ':D' . $highestRowNumber);
			},
		];
	}

	/**
	 * @return int
	 */
	public function chunkSize(): int
	{
		return 10;
	}
}