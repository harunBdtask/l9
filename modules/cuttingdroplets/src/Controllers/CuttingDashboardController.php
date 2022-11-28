<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Controllers;

use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateTableWiseCutProductionReport;

class CuttingDashboardController extends Controller
{
    public function __invoke()
    {
        $date = request()->get('date') ?? now()->toDateString();
		$date_wise_data = $this->getDateWiseReportData($date);

		return view('cuttingdroplets::reports.cutting_dashboard', [
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
}