<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use PDF;
use SkylarkSoft\GoRMG\Cuttingdroplets\Exports\CuttingWiseCuttingReportExport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Exports\DailyCuttingReportExport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Exports\DateWiseCuttingReportExport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Exports\LotWiseCuttingReportExport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Exports\MonthlyTableWiseProductionSummaryReportExport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Exports\MonthWiseCuttingReportExport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingFloor;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingTable;
use SkylarkSoft\GoRMG\Iedroplets\Models\CuttingTarget;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateTableWiseCutProductionReport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateWiseCuttingProductionReport;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;

class DayMonthController extends Controller
{
	public function getMonthWiseCuttingReport(Request $request)
	{
		$reports = null;
		$start_date = $request->from_date ?? now()->startOfMonth()->toDateString();
		$end_Date = $request->to_date ?? date('Y-m-d');

		if ($start_date && $end_Date) {
			$frmDate = Carbon::parse($start_date);
			$toDate = Carbon::parse($end_Date);
			$diff = $frmDate->diffInDays($toDate);

			if ($diff > 30) {
				\Session::flash('error', 'Please enter maximum one month date range');
				return redirect()->back();
			}
			$reports = $this->getMonthWiseReportData($start_date, $end_Date);
		}

		return view('cuttingdroplets::reports.month_wise_report', [
			'from_date' => $start_date,
			'to_date' => $end_Date,
			'reports' => $reports,
		]);
	}

	private function getMonthWiseReportData($from_date, $to_date)
	{
		return DateTableWiseCutProductionReport::whereDate('production_date', '>=', $from_date)
			->whereDate('production_date', '<=', $to_date)
			->selectRaw('cutting_table_id, buyer_id, order_id, garments_item_id, purchase_order_id,
                SUM(cutting_qty - cutting_rejection_qty) as total_cutting_qty
            ')
			->groupBy('cutting_table_id', 'buyer_id', 'order_id', 'garments_item_id', 'purchase_order_id')
			->get();
	}

	public function getMonthWiseReportDownload($type, $from_date, $to_date)
	{
		$data['type'] = $type;
		$data['from_date'] = $from_date;
		$data['to_date'] = $to_date;
		$data['reports'] = $this->getMonthWiseReportData($from_date, $to_date);

		if ($type == 'pdf') {
			$pdf = PDF::setOption('enable-local-file-access', true)
                ->loadView('cuttingdroplets::reports.downloads.month-wise-cutting-report-download', $data)
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

			return $pdf->stream('month-wise-cutting-report.pdf');
		} else {
			return Excel::download(new MonthWiseCuttingReportExport($data), 'month-wise-cutting-report.xlsx');
		}
	}

	public function lotWiseReportForm()
	{
		return view('cuttingdroplets::reports.lot_wise_report');
	}

	public function getLotWiseReportData(Request $request)
	{
		$purchaseOrderId = $request->purchase_order_id ?? null;
		$colorId = $request->color_id ?? null;
		$reports = [];
		if ($purchaseOrderId && $colorId) {
			$reports = $this->getLotWiseReport($purchaseOrderId, $colorId);
		}
		return response()->json($reports);
	}

	public function getLotWiseReport($purchase_order_id, $color_id)
	{
		$lot_wise_data = [];
		BundleCard::query()
			->selectRaw('lot_id, size_id, sum(quantity - total_rejection) as cutting_quantity')
			->with('lot:id,lot_no', 'size:id,name')
			->where([
				'purchase_order_id' => $purchase_order_id,
				'color_id' => $color_id,
				'status' => 1
			])
			->groupBy('lot_id', 'size_id')
			->orderBy('lot_id')
			->get()
			->map(function ($item, $key) use(&$lot_wise_data) {
				$lot_wise_data[] = [
					'lot_no' => $item->lot->lot_no ?? '',
					'size_name' => $item->size->name ?? '',
					'qunatity' => $item->cutting_quantity ?? 0,
				];
			});

		return $lot_wise_data;
	}

	public function getLotWiseCuttingReportDownload(Request $request)
	{
		$type = $request->type;
		$purchaseOrderId = $request->purchase_order_id ?? null;
		$colorId = $request->color_id ?? null;
		$reports = [];
		if ($purchaseOrderId && $colorId) {
			$reports = $this->getLotWiseReport($purchaseOrderId, $colorId);
		} else {
			return redirect()->back();
		}
		$data['results'] = $reports;
		$order_query = PurchaseOrder::where('id', $purchaseOrderId)->first();
		$data['buyer'] = $order_query->buyer->name ?? '';
		$data['booking_no'] = $order_query->order->booking_no ?? '';
		$data['order_style_no'] = $order_query->order->order_style_no ?? '';
		$data['po_no'] = $order_query->po_no ?? '';
		$data['color'] = Color::where('id', $colorId)->first()->name ?? '';
		$data['type'] = $type;
		if ($type == 'pdf') {
			$pdf = PDF::setOption('enable-local-file-access', true)
                ->loadView('cuttingdroplets::reports.downloads.pdf.lot-wise-cutting-report', $data)
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

			return $pdf->stream('lot-wise-cutting-report.pdf');
		} else {
			return Excel::download(new LotWiseCuttingReportExport($data), 'lot-wise-cutting-report.xlsx');
		}
	}

	public function cuttingNoWiseReport()
	{
		return view('cuttingdroplets::reports.cutting_no_wise_report');
	}

	public function getCuttingNoWiseReportData(Request $request)
	{
		$purchaseOrderId = $request->purchase_order_id ?? null;
		$colorId = $request->color_id ?? null;
		$cuttingNo = $request->cutting_no ?? null;
		$report = [];
		if ($purchaseOrderId && $colorId && $cuttingNo) {
			$report = $this->getCuttingNoWiseReport($purchaseOrderId, $colorId, $cuttingNo);
		}
		return response()->json($report);
	}

	public function getCuttingNoWiseReport($purchase_order_id, $color_id, $cutting_no)
	{
		$size_wise_bundle_cards = BundleCard::query()
			->selectRaw(
				'cutting_date, size_id, sum(quantity - total_rejection) as cutting_quantity, count(size_id) as bundle_count'
			)
			->with([
				'size:id,name,sort'
			])
			->where([
				'purchase_order_id' => $purchase_order_id,
				'color_id' => $color_id,
				'cutting_no' => $cutting_no,
				'status' => 1
			])
			->groupBy('size_id', 'cutting_date')
			->orderBy('size_id')
			->get();

        $po_size_ids = PurchaseOrderDetail::query()->select('size_id')->where([
            'purchase_order_id' => $purchase_order_id,
            'color_id' => $color_id,
        ])
        ->orderBy('id', 'asc')
        ->pluck('size_id')
        ->toArray();
		
        $order_size_details = [];
        $exist_sizes = [];
        $s_key = 0;
        foreach ($po_size_ids as $size_id) {
            if (\is_array($exist_sizes) && count($exist_sizes) && \in_array($size_id, $exist_sizes)) {
                continue;
            }
            $sizeExistQuery = $size_wise_bundle_cards->where('size_id', $size_id)->first();
            if ($sizeExistQuery) {
                $order_size_details[$s_key]['name'] = $sizeExistQuery->size->name ?? '';
                $order_size_details[$s_key]['size_sort'] = $sizeExistQuery->size->sort ?? '';
                $order_size_details[$s_key]['count_bundle'] = $sizeExistQuery->bundle_count;
                $order_size_details[$s_key]['size_cutting_qty'] = $sizeExistQuery->cutting_quantity;
                $order_size_details[$s_key]['cutting_date'] = $sizeExistQuery->cutting_date ?? '';
            }
            $exist_sizes[] = $size_id;
            $s_key++;
        }
		
        return $order_size_details;
	}

	public function getCuttingNoWiseCuttingReportDownload(Request $request)
	{
		$type = $request->type ?? null;
		$purchaseOrderId = $request->purchase_order_id ?? null;
		$colorId = $request->color_id ?? null;
		$cuttingNo = $request->cutting_no ?? null;
		$report = [];
		if ($purchaseOrderId && $colorId && $cuttingNo) {
			$report = $this->getCuttingNoWiseReport($purchaseOrderId, $colorId, $cuttingNo);
		} else {
			return redirect()->back();
		}
		$data['order_size_details'] = $report;
		$order_query = PurchaseOrder::where('id', $purchaseOrderId)->first();
		$data['buyer'] = $order_query->buyer->name ?? '';
		$data['style'] = $order_query->order->style_name ?? '';
		$data['po_no'] = $order_query->po_no ?? '';
		$data['color'] = Color::where('id', $colorId)->first()->name ?? '';
		$data['cutting_no'] = $cuttingNo ?? '';
		$data['type'] = $type ?? '';
		if ($type == 'pdf') {
			$pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('cuttingdroplets::reports.downloads.pdf.cutting-no-wise-cutting-report', $data)
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

			return $pdf->stream('cutting-no-wise-cutting-report.pdf');
		} else {
			return Excel::download(new CuttingWiseCuttingReportExport($data), 'cutting-no-wise-cutting-report.xlsx');
		}
	}

	public function getDateWiseReport(Request $request)
	{
		$date = $request->date ?? date('Y-m-d');
		$date_wise_data = $this->getDateWiseReportData($date);

		return view('cuttingdroplets::reports.date_wise_report', [
			'date' => $date,
			'reports' => $date_wise_data,
		]);
	}

	public function getDateWiseReportData($date)
	{
		return DateTableWiseCutProductionReport::whereDate('production_date', $date)
			->selectRaw('cutting_floor_id, cutting_table_id, buyer_id, order_id, garments_item_id, purchase_order_id, color_id,
                SUM(cutting_qty - cutting_rejection_qty) as total_cutting_qty
            ')
			->groupBy('cutting_floor_id', 'cutting_table_id', 'buyer_id', 'order_id', 'garments_item_id', 'purchase_order_id', 'color_id')
			->get();
	}

	public function getDateWiseReportDataOld($date)
	{
		$result_table = [];
		$cutting_result = [];
		$dateWiseReportQuery = DateWiseCuttingProductionReport::where('cutting_date', $date)->get();

		// Order Wise Cutting Production Summary Report
		$order_wise_cutting_summary_report = [];
		$i = 0;
		foreach ($dateWiseReportQuery as $floorKey => $group) {
			foreach ($group->cutting_details as $order) {
				$getOrder = DateWiseCuttingProductionReport::getPurchaseOrder($order['purchase_order_id']);
				$order_wise_cutting_summary_report[$i]['orderKey'] = $i;
				$order_wise_cutting_summary_report[$i]['order_id'] = $getOrder->id;
				$order_wise_cutting_summary_report[$i]['buyer_name'] = $getOrder->buyer->name ?? 'Buyer';
				$order_wise_cutting_summary_report[$i]['booking_no'] = $getOrder->order->booking_no ?? 'Booking No';
				$order_wise_cutting_summary_report[$i]['style_name'] = $getOrder->order->order_style_no ?? 'Order/Style';
				$order_wise_cutting_summary_report[$i]['po_no'] = $getOrder->po_no ?? '';
				$order_wise_cutting_summary_report[$i]['order_qty'] = $getOrder->po_quantity ?? '';
				$order_wise_cutting_summary_report[$i]['cutting_production'] = $order['cutting_qty'] - $order['cutting_rejection'] ?? 0;
				$i++;
			}
		}

		// Color Wise Cutting Production Report
		$color_wise_data = BundleCard::with([
			'cuttingTable:id,table_no',
			'buyer:id,name',
			'order:id,booking_no,order_style_no',
			'purchaseOrder:id,po_no',
			'color:id,name'
		])
			->where([
				'cutting_date' => $date,
				'status' => 1
			])
			->selectRaw('sum(quantity - total_rejection) as color_wise_qty,count(*) as bundle_count,cutting_table_id,buyer_id,order_id,purchase_order_id,color_id')
			->groupBy('cutting_table_id', 'buyer_id', 'order_id', 'purchase_order_id', 'color_id')
			->get();

		/*$color_wise_cutting_summary_report = [];
		$j = 0;
		foreach ($dateWiseReportQuery as $floorKey => $group) {
			foreach ($group->cutting_details as $order) {
				$getOrder = DateWiseCuttingProductionReport::getPurchaseOrder($order['purchase_order_id']);
				$getColor = DateWiseCuttingProductionReport::getColor($order['color_id']);
				$color_wise_cutting_summary_report[$j]['orderKey'] = $j;
				$color_wise_cutting_summary_report[$j]['order_id'] = $getOrder->id;
				$color_wise_cutting_summary_report[$j]['color_id'] = $getColor->id;
				$color_wise_cutting_summary_report[$j]['buyer_name'] = $getOrder->buyer->name ?? 'Buyer';
				$color_wise_cutting_summary_report[$j]['booking_no'] = $getOrder->order->booking_no ?? 'Booking No';
				$color_wise_cutting_summary_report[$j]['style_name'] = $getOrder->order->order_style_no ?? 'Order/Style';
				$color_wise_cutting_summary_report[$j]['color'] = $getColor->name ?? 'Color';
				$color_wise_cutting_summary_report[$j]['po_no'] = $getOrder->po_no ?? '';
				$color_wise_cutting_summary_report[$j]['order_qty'] = $getOrder->po_quantity ?? '';
				$color_wise_cutting_summary_report[$j]['cutting_production'] = $order['cutting_qty'] - $order['cutting_rejection'] ?? 0;
				$j++;
			}
		}*/

		//Cutting Target Wise Production Summary
		$cutting_target_wise_summary_report = [];
		foreach ($dateWiseReportQuery as $floorKey => $group) {
			$cutting_target = $group->cutting_target($group->cutting_table_id, $date) ? $group->cutting_target($group->cutting_table_id, $date)->target : 0;
			$cutting_target_wise_summary_report[$floorKey]['cutting_floor'] = $group->cutting_floors->floor_no ?? 'floor';
			$cutting_target_wise_summary_report[$floorKey]['cutting_table'] = $group->cutting_tables->table_no ?? 'table';
			$cutting_target_wise_summary_report[$floorKey]['cutting_target_per_day'] = $cutting_target;
			$cutting_target_wise_summary_report[$floorKey]['cutting_production'] = $group->total_cutting - $group->total_rejection ?? 0;
			$cutting_target_wise_summary_report[$floorKey]['cutting_percentage'] = $cutting_target ? ($cutting_target == 0 ? '0' : (($group->total_cutting - $group->total_rejection) * 100) / $cutting_target) : 0;
		}

		return [
			'order_wise_cutting_summary_report' => collect($order_wise_cutting_summary_report),
			'color_wise_data' => $color_wise_data,
			'cutting_target_wise_summary_report' => $cutting_target_wise_summary_report,
		];
	}

	public function getDateWiseReportDownload($type, $date)
	{
		$date_wise_data = $this->getDateWiseReportData($date);

		$data = [
			'date' => $date,
			'type' => $type,
			'reports' => $date_wise_data,
		];
		if ($type == 'pdf') {
			$pdf = PDF::setOption('enable-local-file-access', true)
                ->loadView('cuttingdroplets::reports.downloads.date-wise-report-download', $data)
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

			return $pdf->stream('date-wise-report.pdf');
		} else {
			return \Excel::download(new DateWiseCuttingReportExport($data), 'date-wise-report.xlsx');
		}
	}

	public function getDailyCuttingReport(Request $request)
	{
		$date = $request->date ?? date('Y-m-d');
		$cutting_report = $this->getDailyCuttingReportData($date);

		return view('cuttingdroplets::reports.daily_wise_report', [
			'cutting_report' => $cutting_report,
			'date' => $date
		]);
	}

	public function getdailyCuttingReportDownload($type, $date)
	{
		$cutting_report = $this->getDailyCuttingReportData($date);

		if ($type == 'pdf') {
			$pdf = PDF::setOption('enable-local-file-access', true)
                ->loadView('cuttingdroplets::reports.downloads.daily-report-download',
                    compact('cutting_report', 'date')
                )->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

			return $pdf->stream('daily-cutting-report.pdf');
		} else {
			return \Excel::download(new DailyCuttingReportExport($cutting_report, $date), 'daily-cutting-report.xlsx');
		}
	}

	public function getDailyCuttingReportData($date)
	{
		return DateTableWiseCutProductionReport::whereDate('production_date', $date)
			->selectRaw('cutting_floor_id, buyer_id, order_id, purchase_order_id, color_id,
            SUM(cutting_qty - cutting_rejection_qty)  as total_cutting_qty')
            ->where('cutting_qty', '>', 0)
			->groupBy('cutting_floor_id', 'buyer_id', 'order_id', 'purchase_order_id', 'color_id')
			->get();
	}

	public function getDailyCuttingReportDataOld($date)
	{
		$date_wise_cutting_production = DateWiseCuttingProductionReport::where('cutting_date', $date)
			->orderBY('cutting_floor_id', 'asc')
			->get();
		$cutting_report = [];
		$order_id_array = [];
		$counter = 0;
		// Report Variable Initialization
		foreach ($date_wise_cutting_production->groupBY('cutting_floor_id') as $floorKey => $groupByFloor) {
			foreach ($groupByFloor as $group) {
				foreach ($group->cutting_details as $order) {
					if (is_array($order_id_array) && in_array($order['purchase_order_id'], $order_id_array)) {
						continue;
					}
					$order_id_array[$counter] = $order['purchase_order_id'];
					$cutting_report[$floorKey]['cutting_details'][$counter]['buyer_name'] = 'Buyer';
					$cutting_report[$floorKey]['cutting_details'][$counter]['booking_no'] = 'Booking No';
					$cutting_report[$floorKey]['cutting_details'][$counter]['style_name'] = 'Style';
					$cutting_report[$floorKey]['cutting_details'][$counter]['order_no'] = '';
					$cutting_report[$floorKey]['cutting_details'][$counter]['order_qty'] = '';
					$cutting_report[$floorKey]['cutting_details'][$counter]['today_cutting_qty'] = 0;
					$cutting_report[$floorKey]['cutting_details'][$counter]['total_cutting_qty'] = 0;
					$cutting_report[$floorKey]['cutting_details'][$counter]['cutting_balance'] = 0;
					$cutting_report[$floorKey]['cutting_details'][$counter]['input_ready'] = 0;
					$counter++;
				}
			}
		}
		// Report Data calculation
		foreach ($date_wise_cutting_production->groupBY('cutting_floor_id') as $floorKey => $groupByFloor) {
			$cutting_report[$floorKey]['cutting_floor'] = $groupByFloor->first()->cutting_floors->floor_no;
			$cutting_floor_id = $groupByFloor->first()->cutting_floor_id;
			foreach ($groupByFloor as $group) {
				foreach ($group->cutting_details as $order) {
					$getOrder = DateWiseCuttingProductionReport::getPurchaseOrder($order['purchase_order_id']);
					if (is_array($order_id_array) && in_array($getOrder->id, $order_id_array)) {
						$orderKey = array_search($getOrder->id, $order_id_array);
						$cutting_report[$floorKey]['cutting_details'][$orderKey]['buyer_name'] = $getOrder->buyer->name ?? 'Buyer';
						$cutting_report[$floorKey]['cutting_details'][$orderKey]['booking_no'] = $getOrder->order->booking_no ?? 'Booking No';
						$cutting_report[$floorKey]['cutting_details'][$orderKey]['style_name'] = $getOrder->order->order_style_no ?? 'Style';
						$cutting_report[$floorKey]['cutting_details'][$orderKey]['order_no'] = $getOrder->po_no ?? '';
						$cutting_report[$floorKey]['cutting_details'][$orderKey]['order_qty'] = $getOrder->po_quantity ?? '';

						$today_cutting_qty = $cutting_report[$floorKey]['cutting_details'][$orderKey]['today_cutting_qty'] ?? 0;
						$total_cutting_qty = $cutting_report[$floorKey]['cutting_details'][$orderKey]['total_cutting_qty'] ?? 0;

						$cutting_report[$floorKey]['cutting_details'][$orderKey]['today_cutting_qty'] = $today_cutting_qty + $order['cutting_qty'] - $order['cutting_rejection'] ?? 0;
						$cutting_report[$floorKey]['cutting_details'][$orderKey]['total_cutting_qty'] = $total_cutting_qty + $order['cutting_qty'] - $order['cutting_rejection'] ?? 0;

						$cutting_report[$floorKey]['cutting_details'][$orderKey]['cutting_balance'] = $cutting_report[$floorKey]['cutting_details'][$orderKey]['total_cutting_qty'] - $getOrder->po_quantity ?? 0;
						$cutting_report[$floorKey]['cutting_details'][$orderKey]['input_ready'] = $cutting_report[$floorKey]['cutting_details'][$orderKey]['total_cutting_qty'] ?? 0;

						$cutting_report[$floorKey]['cutting_details'][$orderKey]['purchase_order_id'] = $getOrder->id;
						$cutting_report[$floorKey]['cutting_details'][$orderKey]['cutting_floor_id'] = $cutting_floor_id;
					}
				}
			}
		}

		return $cutting_report;
	}

	public function getOrderWiseCuttingProductionSummary($start_date, $end_Date)
	{
		$dateWiseReportQuery = DateWiseCuttingProductionReport::whereDate('cutting_date', '>=', $start_date)->whereDate('cutting_date', '<=', $end_Date)->get();
		// Order Wise Cutting Production Summary Report
		$order_wise_cutting_summary_report = [];
		$i = 0;
		$order_id_array = [];
		foreach ($dateWiseReportQuery as $floorKey => $group) {
			foreach ($group->cutting_details as $order) {
				$getOrder = DateWiseCuttingProductionReport::getPurchaseOrder($order['purchase_order_id']);
				if (is_array($order_id_array) && in_array($getOrder->id, $order_id_array)) {
					//For Same Order
					foreach ($order_wise_cutting_summary_report['cutting_details'] as $findMatchingKey) {
						$is_exist = 0;
						if ($findMatchingKey['order_id'] == $getOrder->id) {
							$is_exist = 1;
							$i = $findMatchingKey['orderKey'];
							break;
						}
					}
					$order_wise_cutting_summary_report['cutting_details'][$i]['orderKey'] = $i;
					$order_wise_cutting_summary_report['cutting_details'][$i]['order_id'] = $getOrder->id;
					$order_wise_cutting_summary_report['cutting_details'][$i]['buyer_name'] = $getOrder->buyer->name ?? 'Buyer';
					$order_wise_cutting_summary_report['cutting_details'][$i]['booking_no'] = $getOrder->order->booking_no ?? 'Booking No';
					$order_wise_cutting_summary_report['cutting_details'][$i]['style_name'] = $getOrder->order->order_style_no ?? 'Order/Style';
					$order_wise_cutting_summary_report['cutting_details'][$i]['order_no'] = $getOrder->po_no ?? '';
					$order_wise_cutting_summary_report['cutting_details'][$i]['order_qty'] = $getOrder->po_quantity ?? '';
					if ($is_exist == 1) {
						$order_wise_cutting_summary_report['cutting_details'][$i]['cutting_production'] += $order['cutting_qty'] - $order['cutting_rejection'] ?? 0;
					} else {
						$order_wise_cutting_summary_report['cutting_details'][$i]['cutting_production'] = $order['cutting_qty'] - $order['cutting_rejection'] ?? 0;
					}
				} else {
					//For New Order
					$order_wise_cutting_summary_report['cutting_details'][$i]['orderKey'] = $i;
					$order_wise_cutting_summary_report['cutting_details'][$i]['order_id'] = $getOrder->id;
					$order_wise_cutting_summary_report['cutting_details'][$i]['buyer_name'] = $getOrder->buyer->name ?? 'Buyer';
					$order_wise_cutting_summary_report['cutting_details'][$i]['booking_no'] = $getOrder->order->booking_no ?? 'Booking No';
					$order_wise_cutting_summary_report['cutting_details'][$i]['style_name'] = $getOrder->order->order_style_no ?? 'Order/Style';
					$order_wise_cutting_summary_report['cutting_details'][$i]['order_no'] = $getOrder->po_no ?? '';
					$order_wise_cutting_summary_report['cutting_details'][$i]['order_qty'] = $getOrder->po_quantity ?? '';
					$order_wise_cutting_summary_report['cutting_details'][$i]['cutting_production'] = $order['cutting_qty'] - $order['cutting_rejection'] ?? 0;
				}
				$order_id_array[$i] = $getOrder->id;
				$i++;
			}
		}
		return $order_wise_cutting_summary_report;
	}

	public function monthlyTableWiseCuttingProductionSummaryReport(Request $request)
	{
		$cutting_floors = CuttingFloor::all()->pluck('floor_no', 'id');
		$cutting_tables = [];
		$cutting_floor_id = $request->cutting_floor_id ?? null;
		$cutting_table_id = $request->cutting_table_id ?? null;
		$month = $request->month ?? (int)date('m');
		$year = $request->year ?? (int)date('Y');
		$reports = null;
		if ($cutting_floor_id && $month && $year) {
			$cutting_tables = CuttingTable::where('cutting_floor_id', $cutting_floor_id)->pluck('table_no', 'id');
			$reports = $this->getMonthlyTableWiseCuttingProductionSummaryReportData($cutting_floor_id, $cutting_table_id, $month, $year);
		}

		return view('cuttingdroplets::reports.monthly_table_wise_cutting_production_summary', [
			'cutting_floors' => $cutting_floors,
			'cutting_tables' => $cutting_tables,
			'cutting_floor_id' => $cutting_floor_id,
			'cutting_table_id' => $cutting_table_id,
			'month' => $month,
			'year' => $year,
			'reports' => $reports,
		]);
	}

	private function getMonthlyTableWiseCuttingProductionSummaryReportData($cutting_floor_id, $cutting_table_id = '', $month, $year)
	{
		return DateTableWiseCutProductionReport::whereMonth('production_date', $month)
			->whereYear('production_date', $year)
			->where('cutting_floor_id', $cutting_floor_id)
			->when($cutting_table_id != '', function ($query) use ($cutting_table_id) {
				return $query->where('cutting_table_id', $cutting_table_id);
			})->selectRaw('production_date, buyer_id, order_id, purchase_order_id, SUM(cutting_qty - cutting_rejection_qty) as cutting_qty_sum')
			->groupBy('production_date', 'buyer_id', 'order_id', 'purchase_order_id')
			->get()
			->filter(function ($item, $key) {
				return $item->cutting_qty_sum > 0;
			});
	}

	public function monthlyTableWiseCuttingProductionSummaryReportDownload(Request $request)
	{
		try {
			$type = $request->type;
			$cutting_floor_id = $request->cutting_floor_id;
			$cutting_table_id = $request->cutting_table_id;
			$year = $request->year;
			$month = $request->month;
			$data['reports'] = $this->getMonthlyTableWiseCuttingProductionSummaryReportData($cutting_floor_id, $cutting_table_id, $month, $year);
			$data['cutting_floor_id'] = $cutting_floor_id;
			$data['cutting_table_id'] = $cutting_table_id;
			$data['cutting_floor_no'] = isset($cutting_floor_id) ? CuttingFloor::findOrFail($cutting_floor_id)->floor_no : null;
			$data['cutting_table_no'] = isset($cutting_table_id) ? CuttingTable::findOrFail($cutting_table_id)->table_no : null;
			$data['year'] = $year;
			$data['month'] = $month;

			if ($type == 'pdf') {
				$pdf = \PDF::setOption('enable-local-file-access', true)
                    ->loadView('cuttingdroplets::reports.downloads.pdf.monthly_table_wise_production_summary_report_download', $data)
                    ->setPaper('a4')->setOptions([
                        'header-html' => view('skeleton::pdf.header'),
                        'footer-html' => view('skeleton::pdf.footer'),
                    ]);

				return $pdf->stream('monthly-table-wise-production-summary-report.pdf');
			} else {
				return \Excel::download(new MonthlyTableWiseProductionSummaryReportExport($data), 'monthly-table-wise-production-summary-report.xlsx');
			}
		} catch (\Exception $e) {
			return redirect('/monthly-table-wise-production-summary-report');
		}
	}
}
