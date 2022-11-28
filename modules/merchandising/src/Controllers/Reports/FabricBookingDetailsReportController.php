<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetailsBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Services\Report\FabricBookings\FabricBookingDetailsReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;


class FabricBookingDetailsReportController extends Controller
{

    public function index()
    {
        $buyers = Buyer::query()->pluck('name', 'id');
        $merchandisers = User::query()->pluck('screen_name', 'id');
        $fabric_bookings = FabricBooking::query()->pluck('unique_id', 'id');
        $details_id = FabricBookingDetailsBreakdown::query()->pluck('job_no', 'id');
        $styles = Order::query()->pluck('style_name', 'style_name');
        $types = [
            1 => 'Booking Date',
            2 => 'Delivery Date',
        ];

        return view('merchandising::reports.fabric_bookings.summery_report.index', [
            'buyers' => $buyers,
            'merchandisers' => $merchandisers,
            'booking_ids' => $fabric_bookings,
            'details_ids' => $details_id,
            'styles' => $styles,
            'types' => $types,
        ]);
    }

    public function reportData(Request $request, FabricBookingDetailsReportService $service)
    {
        $reportData = $service->getReportData($request);

        return view('merchandising::reports.fabric_bookings.summery_report.includes.table', [
            'reportData' => $reportData,
        ]);
    }

    public function pdf(Request $request, FabricBookingDetailsReportService $service)
    {
        $reportData = $service->getReportData($request);

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('merchandising::reports.fabric_bookings.summery_report.pdf', [
                'reportData' => $reportData,
            ])
            ->setPaper('a4')->setOrientation('landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream('fabric_booking_details.pdf');
    }

}
