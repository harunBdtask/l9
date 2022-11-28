<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Controllers\V2;

use App\Http\Controllers\Controller;
use Excel;
use Illuminate\Http\Request;
use PDF;
use SkylarkSoft\GoRMG\Cuttingdroplets\Exports\V2\DateWiseCuttingReportExport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateTableWiseCutProductionReport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingFloor;

class DateWiseProductionReportController extends Controller
{
    public function dateWiseReport(Request $request)
    {
        $date = $request->date ?? now()->toDateString();
        $floors = CuttingFloor::query()->pluck('floor_no', 'id');
        $floors = $floors->prepend('Select', 0);
        $floor = $request->get('floor') ?? null;

        $reports = $this->getDateWiseReportData($date, $floor);

        return view('cuttingdroplets::reports.v2.date_wise_cutting_report', [
            'date' => $date,
            'floors' => $floors,
            'floor' => $floor,
            'reports' => $reports,
        ]);
    }

    private function getDateWiseReportData($date, $floor = null)
    {
        return DateTableWiseCutProductionReport::query()
            ->with([
                'cuttingFloor:id,floor_no',
                'cuttingTable:id,table_no',
                'buyer:id,name',
                'order:id,style_name,dealing_merchant_id,garments_item_group,combo',
                'garmentsItem:id,name',
                'color:id,name',
                'purchaseOrder:id,po_no,country_id',
                'purchaseOrder.country:id,name',
            ])
            ->when($floor, function ($query) use ($floor) {
                $query->where('cutting_floor_id', $floor);
            })
            ->whereDate('production_date', $date)
            ->selectRaw('cutting_floor_id, cutting_table_id, buyer_id, order_id, garments_item_id, purchase_order_id, color_id,
                SUM(cutting_qty - cutting_rejection_qty) as today_cutting_qty
            ')
            ->groupBy('cutting_floor_id', 'cutting_table_id', 'buyer_id', 'order_id', 'garments_item_id', 'purchase_order_id', 'color_id')
            ->orderBy('order_id')
            ->get()
            ->map(function ($report) use ($date) {
                $totalProductionQuery = TotalProductionReport::getColorWiseTotal($report->purchase_order_id, $report->color_id);
                $total_cutting_qty = $totalProductionQuery ? ($totalProductionQuery->total_cutting - $totalProductionQuery->total_cutting_rejection) : 0;
                $merchandiser = $report->order->dealingMerchant ? $report->order->dealingMerchant->screen_name : '';
                $itemGroup = $report->order->garments_item_group ? $report->order->garmentsItemGroup->name : '';
                $orderQty = PurchaseOrderDetail::getColorWisePoQuantity($report->purchase_order_id, $report->color_id);
                $ecqQty = 'N/A';

                return [
                    'cutting_floor_id' => $report->cutting_floor_id,
                    'cutting_table_id' => $report->cutting_table_id,
                    'buyer_id' => $report->buyer_id,
                    'order_id' => $report->order_id,
                    'garments_item_id' => $report->garments_item_id,
                    'purchase_order_id' => $report->purchase_order_id,
                    'color_id' => $report->color_id,
                    'floor' => $report->cuttingFloor->floor_no,
                    'table' => $report->cuttingTable->table_no,
                    'merchandiser' => $merchandiser,
                    'buyer_name' => $report->buyer->name,
                    'style_name' => $report->order->style_name,
                    'item' => $report->garmentsItem->name,
                    'item_group' => $itemGroup,
                    'po_no' => $report->purchaseOrder->po_no,
                    'color_name' => $report->color->name,
                    'combo' => $report->order->combo,
                    'country' => $report->purchaseOrder->country->name,
                    'order_qty' => $orderQty,
                    'ecq_qty' => $ecqQty,
                    'today_cutting_qty' => $report->today_cutting_qty,
                    'total_cutting_qty' => $total_cutting_qty,
                ];
            });
    }

    public function dateWiseReportDownload(Request $request, $type, $date)
    {
        $data['date'] = $date;
        $floor = $request->get('floor') ?? null;

        $data['reports'] = $this->getDateWiseReportData($date, $floor);
        if ($type == 'pdf') {
            $pdf = PDF::setOption('enable-local-file-access', true)
                ->loadView('cuttingdroplets::reports.downloads.pdf.v2.date_wise_cutting_report', $data)
                ->setPaper('a4')->setOrientation('landscape')
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer')
                ]);
            return $pdf->stream('date_wise_cutting_report' . date('d_m_Y', strtotime($date)) . '.pdf');
        } else {
            return Excel::download(new DateWiseCuttingReportExport($data), 'date_wise_cutting_report' . date('d_m_Y', strtotime($date)) . '.xlsx');
        }
    }
}
