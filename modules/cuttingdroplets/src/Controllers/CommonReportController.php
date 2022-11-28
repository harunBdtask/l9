<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use PDF;
use Session;
use SkylarkSoft\GoRMG\Cuttingdroplets\Exports\BundleScanCheckReportExport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Exports\LineWiseCuttingReportExport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Exports\TableWiseCuttingReportSummeryExport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingFloor;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateTableWiseCutProductionReport;
use SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport;
use SkylarkSoft\GoRMG\Inputdroplets\Models\Floor;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;

class CommonReportController extends Controller
{

	public function getBundleScanCheck()
	{
		$buyers = Buyer::pluck('name', 'id')->all();

		return view('cuttingdroplets::reports.bundle_scan_check')
			->with('buyers', $buyers);
	}

	public function bundleScanCheckData(Request $request)
	{
		$purchase_order_id = $request->purchase_order_id ?? null;
		$color_id = $request->color_id ?? null;
		$cutting_no = $request->cutting_no ?? null;
		$report = $this->getBundleScanCheckData($purchase_order_id, $color_id, $cutting_no);

		return response()->json($report);
	}

	public function getBundleScanCheckData($purchase_order_id, $color_id, $cutting_no)
	{
		$bundle_card_list = BundleCard::with('details:id,is_manual')
        ->where([
			'purchase_order_id' => $purchase_order_id,
			'color_id' => $color_id,
			'cutting_no' => $cutting_no
		])->with([
			'cutting_inventory:id,bundle_card_id,print_status,created_at,updated_at',
			'print_inventory:id,bundle_card_id,created_at',
			'sewingoutput:id,bundle_card_id,created_at',
			'washing:id,bundle_card_id,created_at',
			'size:id,name'
		])->get();

		$bundle_cards = [];
		foreach ($bundle_card_list as $key => $bundle) {
			$bundle_cards[$key]['sl'] = ++$key;
			$bundle_cards[$key]['barcode'] = str_pad($bundle->id, 8, '0', STR_PAD_LEFT);
			$bundle_cards[$key]['quantity'] = $bundle->quantity - $bundle->total_rejection;
			$bundle_cards[$key]['cutting'] = $bundle->status ? 'Scanned' : 'Not';
			$bundle_cards[$key]['cutting_date'] = $bundle->cutting_date ?? '';
			$bundle_cards[$key]['size'] = $bundle->size->name ?? '';
			$bundle_cards[$key]['bundle_no'] = $bundle->details->is_manual == 1 ? $bundle->size_wise_bundle_no : ($bundle->{getbundleCardSerial()} ?? $bundle->bundle_no ?? $bundle->size_wise_bundle_no ?? '');

			if ($bundle->print_inventory) {
				$bundle_cards[$key]['print_sent'] = 'Scanned';
				$bundle_cards[$key]['print_sent_datetime'] = $bundle->print_inventory->created_at->format('d-m-Y h:ia');
			} else {
				$bundle_cards[$key]['print_sent'] = 'Not';
				$bundle_cards[$key]['print_sent_datetime'] = '';
			}

			if ($bundle->cutting_inventory
				&& ($bundle->cutting_inventory->print_status == 1
					|| $bundle->cutting_inventory->print_status == 2)) {
				$bundle_cards[$key]['print_received'] = 'Scanned';
				$bundle_cards[$key]['print_received_datetime'] = $bundle->cutting_inventory->updated_at->format('d-m-Y h:ia');
			} else {
				$bundle_cards[$key]['print_received'] = 'Not';
				$bundle_cards[$key]['print_received_datetime'] = '';
			}

			if ($bundle->cutting_inventory) {
				$bundle_cards[$key]['cutting_inventory'] = 'Scanned';
				$bundle_cards[$key]['cutting_inventory_datetime'] = $bundle->cutting_inventory->created_at->format('d-m-Y h:ia');
			} else {
				$bundle_cards[$key]['cutting_inventory'] = 'Not';
				$bundle_cards[$key]['cutting_inventory_datetime'] = '';
			}

			if ($bundle->sewingoutput) {
				$bundle_cards[$key]['sewingoutput'] = 'Scanned';
				$bundle_cards[$key]['sewingoutput_datetime'] = $bundle->sewingoutput->created_at->format('d-m-Y h:ia');
			} else {
				$bundle_cards[$key]['sewingoutput'] = 'Not';
				$bundle_cards[$key]['sewingoutput_datetime'] = '';
			}

			if ($bundle->washing) {
				$bundle_cards[$key]['washing_sent'] = 'Scanned';
				$bundle_cards[$key]['washing_sent_datetime'] = $bundle->washing->created_at->format('d-m-Y h:ia');
			} else {
				$bundle_cards[$key]['washing_sent'] = 'Not';
				$bundle_cards[$key]['washing_sent_datetime'] = '';
			}
		}
		return collect($bundle_cards);
	}

	public function getBundlecardScanCheckReportDownload(Request $request)
	{
		$type = $request->type;
		$purchase_order_id = $request->purchase_order_id ?? null;
		$color_id = $request->color_id ?? null;
		$cutting_no = $request->cutting_no ?? null;

		$data['bundle_cards'] = $this->getBundleScanCheckData($purchase_order_id, $color_id, $cutting_no);
		$purchaseOrder = PurchaseOrder::where('id', $purchase_order_id)->first() ?? '';
		$data['buyer'] = $purchaseOrder->buyer->name ?? '';
		$data['style'] = $purchaseOrder->order->order_style_no ?? '';
		$data['order_no'] = $purchaseOrder->po_no ?? '';
		$data['color'] = Color::where('id', $color_id)->first()->name ?? '';
		$data['cutting_no'] = $cutting_no;
		if ($type == 'pdf') {
			$pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('cuttingdroplets::reports.downloads.pdf.bundle-card-scancheck-report-download', $data)
                ->setPaper('a4')->setOrientation('landscape')->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

			return $pdf->stream('bundlecard-scan-check-report.pdf');
		} else {
			return \Excel::download(new BundleScanCheckReportExport($data), 'bundlecard-scan-check-report.xlsx');
		}
	}

	public function bookingBalanceBundleScanCheck(Request $request)
	{
        $buyer_id = $request->buyer_id ?? null;
		$order_id = $request->order_id ?? null;
		$buyers = $buyer_id ? Buyer::query()->where('id', $buyer_id)->pluck('name', 'id') : [];
		$orders = $order_id ? Order::query()->where('id', $order_id)->pluck('style_name', 'id') : [];
		$reports = $order_id ? $this->getBookingBalanceBundleScanCheckData($order_id) : null;

		return view('cuttingdroplets::reports.booking_balance_bundle_scan_check', [
			'buyers' => $buyers,
			'orders' => $orders,
			'buyer_id' => $buyer_id,
			'order_id' => $order_id,
			'reports' => $reports,
		]);
	}

	public function getBookingBalanceBundleScanCheckData($order_id)
	{
		$bundle_card_list = BundleCard::where([
			'order_id' => $order_id,
			'status' => 1,
		]);
		$bundle_card_input_exist = clone $bundle_card_list;
		if ($bundle_card_input_exist->whereNotNull('cutting_date')
			->whereNotNull('input_date')
			->count() <= 0) {
			return null;
		}
		$bundle_card_list_query = clone $bundle_card_list;
		$sewing_wip_list_query = clone $bundle_card_list;

		$cutting_wip_list = $bundle_card_list_query->whereNotNull('cutting_date')
			->whereNull('input_date')
			->get();

		$sewing_wip_list = $sewing_wip_list_query->whereNotNull('input_date')
			->whereNull('sewing_output_date')
			->get();

		$total_production_related_query = BundleCard::where([
			'order_id' => $order_id,
			'status' => 1,
		])->whereNotNull('cutting_date');

		$total_production_related_query_clone = clone $total_production_related_query;
		$total_input_related_query_clone = clone $total_production_related_query;
		$total_output_related_query_clone = clone $total_production_related_query;

		$total_cutting_bundles = $total_production_related_query_clone->count();
		$total_cutting_qty = $total_production_related_query_clone->sum('quantity') - $total_production_related_query_clone->sum('total_rejection');

		$total_input_related_query = $total_input_related_query_clone->where('input_date', '!=', null);
		$total_input_bundles = $total_input_related_query->count();
		$total_input_qty = $total_input_related_query->sum('quantity') - $total_input_related_query->sum('total_rejection') - $total_input_related_query->sum('print_rejection') - $total_input_related_query->sum('embroidary_rejection');

		$total_output_related_query = $total_output_related_query_clone->where('sewing_output_date', '!=', null);
		$total_output_bundles = $total_output_related_query->count();
		$total_output_rejection = $total_output_related_query->sum('sewing_rejection');
		$total_output_qty = $total_output_related_query->sum('quantity') - $total_output_related_query->sum('total_rejection') - $total_output_related_query->sum('print_rejection') - $total_output_related_query->sum('embroidary_rejection') - $total_output_rejection;

		return [
			'bundle_card_list' => $sewing_wip_list,
			'cutting_wip_list' => $cutting_wip_list,
			'total_cutting_bundles' => $total_cutting_bundles,
			'total_cutting_qty' => $total_cutting_qty,
			'total_input_bundles' => $total_input_bundles,
			'total_input_qty' => $total_input_qty,
			'total_output_bundles' => $total_output_bundles,
			'total_output_qty' => $total_output_qty,
			'total_output_rejection' => $total_output_rejection,
		];
	}

	public function getChallansByBundlecard(Request $request)
	{
		$bundleInfo = BundleCard::with([
			'print_inventory:id,bundle_card_id,challan_no',
			'cutting_inventory:id,bundle_card_id,challan_no',
			'sewingoutput:id,bundle_card_id,output_challan_no',
		])
			->where('id', substr($request->bundlecard, 1, 9))
			->first();

		if (!$bundleInfo && $request->bundlecard) {
			Session::flash('error', 'Please enter valid bundlecard');
		}

		return view('cuttingdroplets::pages.challans_by_bundlecard', [
			'bundlecard' => $request->bundlecard ?? '',
			'bundleInfo' => $bundleInfo ?? null
		]);
	}

	public function individualBundleScanCheck(Request $request)
	{
		$bundleInfo = BundleCard::with([
			'print_inventory:id,bundle_card_id,created_at,updated_at',
			'cutting_inventory:id,bundle_card_id,created_at,updated_at,print_status',
			'sewingoutput:id,bundle_card_id,created_at',
		])
			->where('id', substr($request->bundlecard, 1, 9))
			->first();

		if (!$bundleInfo && $request->bundlecard) {
			Session::flash('error', 'Please enter valid bundlecard');
		}

		return view('cuttingdroplets::pages.individual_bundle_scan_check', [
			'bundlecard' => $request->bundlecard ?? '',
			'bundleInfo' => $bundleInfo ?? null
		]);
	}


	public function floorLineWiseCuttingReport(Request $request)
	{
		$buyer_id = $request->buyer_id ?? null;
		$order_id = $request->order_id ?? null;
		$date = $request->date ?? null;
		$thisDate = date('Y-m-d');
		$floor_line_wise_reports = $this->lineWiseInputInHandReportData($thisDate, $date, $buyer_id, $order_id);
		$buyers = Buyer::all()->pluck('name', 'id');
		$orders = [];
		if ($buyer_id) {
			$orders = Order::where('buyer_id', $buyer_id)->pluck('booking_no', 'id');
		}

		return view('cuttingdroplets::reports.floor_line_wise_report', [
			'floor_line_wise_reports' => $floor_line_wise_reports ?? null,
			'buyers' => $buyers,
			'orders' => $orders,
			'buyer_id' => $buyer_id,
			'order_id' => $order_id,
			'date' => $date,
		]);
	}

	public function lineWiseInputInHandReportData($thisDate, $date = '', $buyer_id = '', $order_id = '')
	{
		$from_date = Carbon::today()->subDay(120)->toDateString();
		return FinishingProductionReport::with(
			[
				'orderColorWiseInput:id,production_date,order_id,color_id,sewing_input',
				'order:id,booking_no,order_style_no,excess_cutting_percent',
				'buyer:id,name',
				'color:id,name',
				'floor:id,floor_no',
				'line:id,line_no',
			])
			->select(
				[
					'order_id',
					'buyer_id',
					'color_id',
					'floor_id',
					'line_id'
				])
			->where(function($query) {
				return $query->where('sewing_input', '>', 0)
					->orWhere('sewing_output', '>', 0);
			})
			->when($buyer_id != '', function ($query) use ($buyer_id) {
				return $query->where('buyer_id', $buyer_id);
			})
			->when($order_id != '', function ($query) use ($order_id) {
				return $query->where('order_id', $order_id);
			})
			->when((($buyer_id != '' || $order_id != '') && $date == ''), function ($query) use($from_date) {
				return $query->whereDate('production_date', '>=', $from_date);
			})
			->when(($buyer_id == '' && $order_id == '' && $date == ''), function ($query) use ($thisDate) {
				return $query->whereDate('production_date', $thisDate);
			})
			->when($date != '', function ($query) use ($date) {
				return $query->whereDate('production_date', $date);
			})

			->groupBy('order_id', 'buyer_id', 'color_id', 'floor_id', 'line_id')
			->get();
	}

	public function floorLineWiseCuttingReportDownload(Request $request)
	{
		$buyer_id = $request->buyer_id ?? null;
		$order_id = $request->order_id ?? null;
		$date = $request->date ?? null;
		$thisDate = date('Y-m-d');
		$data['floor_line_wise_reports'] = $this->lineWiseInputInHandReportData($thisDate, $date, $buyer_id, $order_id);
		$data['type'] = request('type');
		if (request('type') == 'pdf') {

			/*$pdf = \PDF::loadView('sewingdroplets::reports.downloads.pdf.floor_line_wise_sewing_report_download', $data, [], [
				'format' => 'A4-L'
			]);*/

			$pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('cuttingdroplets::reports.downloads.pdf.floor_line_wise_cutting_report_download',
                    $data, ['mode' => 'utf-8', 'format' => [233, 500]]
                )->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

			return $pdf->stream('line-wise-input-inhand-cutting-report.pdf');
			//return $pdf->download('line-wise-sewing-output-report.pdf');

		} else {
			return \Excel::download(new LineWiseCuttingReportExport($data), 'line-wise-input-inhand-cutting-report.xlsx');
		}
	}

	public function searchBundleCardGenerations(Request $request)
	{
		$floorId = $request->floor_id ?? 'all';
		$floors = Floor::pluck('floor_no', 'id')->prepend('All Floor', 'all'); // $floors = sewing floor

		$fromDate = $request->from_date ?? Carbon::now()->subDays(45)->toDateString();
		$toDate = $request->to_date ?? Carbon::now()->toDateString();
		$floor_line_wise_report = FinishingProductionReport::withoutGlobalScope('factoryId')
			->with('buyer', 'order', 'purchaseOrder', 'color', 'floor', 'line', 'todayLiseWiseTarget')
			->select('finishing_production_reports.*')
			->leftJoin('orders', 'orders.id', 'finishing_production_reports.order_id')
			->leftJoin('buyers', 'buyers.id', 'finishing_production_reports.buyer_id')
			->leftJoin('purchase_orders', 'purchase_orders.id', 'finishing_production_reports.purchase_order_id')
			->leftJoin('colors', 'colors.id', 'finishing_production_reports.color_id')
			->leftJoin('floors', 'floors.id', 'finishing_production_reports.floor_id')
			->where('finishing_production_reports.factory_id', factoryId())
			->where(function ($query) use ($request) {
				$query->orWhere('finishing_production_reports.id', 'like', '%' . $request->q . '%')
					->orWhere('buyers.name', 'like', '%' . $request->q . '%')
					->orWhere('floors.floor_no', 'like', '%' . $request->q . '%')
					->orWhere('orders.order_style_no', 'like', '%' . $request->q . '%')
					->orWhere('orders.booking_no', 'like', '%' . $request->q . '%');
			})
			->orderBy('finishing_production_reports.id', 'DESC')
			->paginate();

		return view('cuttingdroplets::reports.floor_line_wise_report', ['floor_line_wise_report' => $floor_line_wise_report, 'floor_id' => $floorId, 'floors' => $floors, 'from_date' => $fromDate,
			'to_date' => $toDate, 'q' => $request->q]);

	}

	public function CuttingProductionSummaryReport(Request $request)
	{

		$cutting_floors = CuttingFloor::all()->pluck('floor_no', 'id');
		$buyers = Buyer::all()->pluck('name', 'id');
		$cutting_floor_id = $request->cutting_floor_id ?? null;
		$order_id = $request->order_id ?? null;
		$buyer_id = $request->buyer_id ?? null;
        $orders = $buyer_id ? Order::query()->where('buyer_id', $buyer_id)->pluck('style_name', 'id') : [];
		$reports = null;
		if ($buyer_id) {
			$reports = $this->getMonthlyTableWiseCuttingProductionSummaryReportData($buyer_id, $order_id);
		}

        return view('cuttingdroplets::reports.table_wise_cutting_production_summary', [
			'cutting_floors' => $cutting_floors,
			'orders' => $orders,
			'cutting_floor_id' => $cutting_floor_id,
			'order_id' => $order_id,
			'buyer_id' => $buyer_id,
			'buyers' => $buyers,
			'reports' => $reports,
		]);
	}

	private function getMonthlyTableWiseCuttingProductionSummaryReportData($buyer_id, $order_id = '')
	{

		return DateTableWiseCutProductionReport::with('cuttingTable')
			->when($buyer_id != '', function ($query) use ($buyer_id) {
				return $query->where('buyer_id', $buyer_id);
			})
			->when($order_id != '', function ($query) use ($order_id) {
				return $query->where('order_id', $order_id);
			})->selectRaw('production_date, buyer_id, order_id,cutting_table_id, purchase_order_id, SUM(cutting_qty - cutting_rejection_qty) as cutting_qty_sum')
			->groupBy('production_date', 'buyer_id', 'order_id', 'cutting_table_id', 'purchase_order_id')
			->get()
			->filter(function ($item, $key) {
				return $item->cutting_qty_sum > 0;
			});
	}

	public function monthlyTableWiseCuttingProductionSummaryReportDownload(Request $request)
	{
		if (request('buyer_id') || request('order_table_id')) {

			$type = $request->type;
			$order_table_id = $request->order_table_id;
			$buyer_id = $request->buyer_id;
			$data['order_table_id'] = $order_table_id;
			$data['buyer_id'] = $buyer_id;

			$data['reports'] = $this->getMonthlyTableWiseCuttingProductionSummaryReportData($buyer_id, $order_table_id);

			if (request('type') == 'pdf') {

				$pdf = \PDF::setOption('enable-local-file-access', true)
                    ->loadView('cuttingdroplets::reports.downloads.pdf.table_wise_production_summary_report_download', $data)
                    ->setPaper('a4')->setOptions([
                        'header-html' => view('skeleton::pdf.header'),
                        'footer-html' => view('skeleton::pdf.footer'),
                    ]);

				return $pdf->stream('daily-table-wise-cutting-report-summary.pdf');

			} else {
				return \Excel::download(new TableWiseCuttingReportSummeryExport($data), 'daily-table-wise-cutting-report-summary.xlsx');
			}
		} else {
			return redirect()->back();
		}
	}

}
