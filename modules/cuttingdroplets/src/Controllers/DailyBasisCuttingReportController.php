<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationView;
use SkylarkSoft\GoRMG\Cuttingdroplets\Services\DailyBasisCuttingService;
use SkylarkSoft\GoRMG\Cuttingdroplets\Services\DailyCuttingBalanceReportService;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;
use SkylarkSoft\GoRMG\Merchandising\Filters\Filter;

class DailyBasisCuttingReportController
{

    public function index(Request $request)
    {
        return view('cuttingdroplets::reports.daily-basis-cutting-report.index');
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function getReport(Request $request)
    {
        $request->validate([
            'po_id' => 'required',
        ]);

        $data = (new DailyBasisCuttingService())->response($request);

        return view('cuttingdroplets::reports.daily-basis-cutting-report.data-table', $data);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function pdf(Request $request)
    {
        $data = (new DailyBasisCuttingService())->response($request);
        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('cuttingdroplets::reports.daily-basis-cutting-report.pdf', $data)
            ->setPaper('a4')->setOrientation('landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer')
            ]);

        return $pdf->stream('daily_basis_cutting_report.pdf');
    }
}
