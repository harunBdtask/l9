<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\Cuttingdroplets\Exports\ConsumptionReportExport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationDetail;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationView;

class BundleCardConsumptionReportController extends Controller
{
    public function consumptionReport(Request $request)
    {
        $buyerId = $request->buyer_id ?? null;
        $orderId = $request->order_id ?? null;

        $bundleCardGenerationDetails = $this->consumptionReportData($buyerId, $orderId);

        $buyers = $buyerId ? Buyer::query()->where('id', $buyerId)->pluck('name', 'id') : [];
        $orders = $orderId ? Order::query()->where('id', $orderId)->pluck('style_name', 'id') : [];

        return view('cuttingdroplets::reports.consumption', [
            'bundleCardGenerationDetails' => $bundleCardGenerationDetails,
            'buyers' => $buyers,
            'orders' => $orders,
            'buyer_id' => $request->buyer_id ?? null,
            'order_id' => $request->order_id ?? null,
        ]);
    }

    public function consumptionReportData($buyerId, $orderId)
    {
        $query = BundleCardGenerationView::query();
        if (($buyerId && $orderId)) {
            $query = BundleCardGenerationDetail::query();
        }

        $bundleCardGenerationDetails = $query->with([
            'bundleCardsGetColors:bundle_card_generation_detail_id,color_id',
            'bundleCardsGetColors.color:id,name',
        ])
            ->select([
                'sid',
                'rolls',
                'lot_ranges',
                'booking_dia',
                'ratios',
                'po_details',
                'max_quantity',
                'is_tube',
                'booking_consumption',
            ])
            ->where('is_regenerated', 0)
            ->where('is_manual', 0)
            ->when($buyerId, function ($query) use ($buyerId) {
                $query->where('buyer_id', $buyerId);
            })
            ->when($orderId, function ($query) use ($orderId) {
                $query->where('order_id', $orderId);
            })
            ->when((!$buyerId && !$orderId), function ($query) {
                $query->whereDate('created_at', '>=', now()->subDays(15)->toDateString());
            })
            ->orderBy('id', 'DESC')
            ->paginate();
        return $bundleCardGenerationDetails;
    }

    public function consumptionReportDownload(Request $request)
    {
        $type = $request->type;
        $buyer_id = $request->buyer_id;
        $order_id = $request->order_id;
        $current_page = $request->current_page;
        $data['buyer'] = $buyer_id ? Buyer::findOrFail($buyer_id)->name : "";
        $data['booking_no'] = $order_id ? Order::findOrFail($order_id)->booking_no : "";
        Paginator::currentPageResolver(function () use ($current_page) {
            return $current_page;
        });
        $data['bundleCardGenerationDetails'] = $this->consumptionReportData($buyer_id, $order_id);
        if ($type == 'pdf') {
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('cuttingdroplets::reports.downloads.pdf.consumption-report-download', $data)
                ->setPaper('a4')->setOrientation('landscape')->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('consumption-report.pdf');
        } else {
            return \Excel::download(new ConsumptionReportExport($data), 'consumption-report.xlsx');
        }
    }
}
