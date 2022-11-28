<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings;

use App\Http\Controllers\Controller;
use PDF;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Log;
use SkylarkSoft\GoRMG\Merchandising\Actions\ServiceBookingNotification;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricServiceBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Services\Booking\FabricServiceBookingReportService;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\MerchandisingVariableSettings;
use SkylarkSoft\GoRMG\SystemSettings\Models\TermsAndCondition;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class FabricServiceBookingController extends Controller
{
    public function index()
    {
        $paginateNumber = request('paginateNumber') ?? 15;

        $bookings = FabricServiceBooking::with('factory', 'buyer', 'supplier')
            ->userWiseBuyerFilter()
            ->factoryWiseFilter()
            ->latest()
            ->paginate($paginateNumber);

        \request()->flash();
        $totalFabricBookings = FabricServiceBooking::all()->count();
        $searchedOrders = 15;
        $dashboardOverview = [
            "Total Fabric Service Booking" => $totalFabricBookings
        ];

        return view('merchandising::booking.fabric-booking-service.index', compact('bookings', 'dashboardOverview', 'paginateNumber', 'searchedOrders'));
    }

    public function create()
    {
        return view('merchandising::booking.fabric-booking-service.create');
    }

    public function store(Request $request)
    {
        $this->validateFabricServiceBookingRequest($request);

        try {
            $booking = new FabricServiceBooking($request->all(
                'factory_id',
                'buyer_id',
                'supplier_id',
                'booking_date',
                'delivery_date',
                'pay_mode',
                'source',
                'exchange_rate',
                'attention',
                'label',
                'ready_to_approve',
                'unapproved_request',
                'process',
                'currency'
            ));
            $booking->save();

            return response()->json([
                'message' => 'Saved Successfully!',
                'booking' => $booking,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::info($e->getMessage());

            return response()->json(['message' => 'Failed!', 'info' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function show(FabricServiceBooking $booking)
    {
        return \response()->json(['booking' => $booking]);
    }

    public function update(FabricServiceBooking $booking, Request $request)
    {
        $this->validateFabricServiceBookingRequest($request);

        try {
            $booking->update($request->all([
                'factory_id',
                'buyer_id',
                'supplier_id',
                'booking_date',
                'delivery_date',
                'pay_mode',
                'source',
                'exchange_rate',
                'attention',
                'label',
                'ready_to_approve',
                'unapproved_request',
                'process',
                'currency',
            ]));

            ServiceBookingNotification::send($booking);

            return response()->json(['message' => 'Update Successfully!'], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::info($e->getMessage());

            return response()->json(['message' => 'Failed!'], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param $id
     * @return JsonResponse|RedirectResponse
     * @throws Throwable
     */
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $fabricServiceBooking = FabricServiceBooking::query()->findOrFail($id);
            $fabricServiceBooking->details()->delete();
            $fabricServiceBooking->delete();
            DB::commit();
            Session::flash('error', 'Deleted Successfully');

            return redirect()->back();
        } catch (Exception $e) {
            Log::info('FabricServiceBooking Delete: ' . $e->getMessage() . ':' . $e->getLine());

            return response()->json(['message' => 'Failed!', 'info' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    private function validateFabricServiceBookingRequest(Request $request)
    {
        $request->validate([
            'factory_id' => 'required',
            'buyer_id' => 'required',
            'supplier_id' => 'required',
            'booking_date' => 'required',
            'pay_mode' => 'required',
            'label' => 'required',
            'process' => 'required',
        ]);
    }

    public function budgetSearch(Request $request)
    {
        $request->validate([
            'factory_id' => 'required',
            'buyer_id' => 'required',
        ]);

        try {
            $factoryId = $request->get('factory_id');
            $buyerId = $request->get('buyer_id');
            $year = $request->get('year');
            $jobNo = $request->get('unique_id');
            $internalRefNo = $request->get('ref_no');
            $fileNo = $request->get('file_no');
            $styleName = $request->get('style_name');
            $PONo = $request->get('order_no');
            $dateFrom = $request->get('date_from');
            $dateTo = $request->get('date_to');
            $label = $request->get('label');
            $bookingId = $request->get('booking_id');

            $fabricActionStatus = MerchandisingVariableSettings::query()->where(['factory_id' => $factoryId, 'buyer_id' => $buyerId])->first();
            $fabricActionStatus = isset($fabricActionStatus) ? $fabricActionStatus['variables_details']['budget_approval_required_for_booking']['fabric_part'] : null;

            $budgets = Budget::with(['order.purchaseOrders' => function ($q) use ($PONo) {
                $q->when($PONo, function ($query) use ($PONo) {
                    $query->where('po_no', $PONo);
                });
            }, /*'fabricCosting',*/ 'factory:id,factory_name', 'buyer:id,name'])
                ->when($jobNo, function ($query) use ($jobNo) {
                    $query->where('job_no', 'like', "%{$jobNo}%");
                })
                ->when($styleName, function ($query) use ($styleName) {
                    $query->where('style_name', $styleName);
                })
                ->when($factoryId, function ($query) use ($factoryId) {
                    $query->where('factory_id', $factoryId);
                })
                ->when($buyerId, function ($query) use ($buyerId) {
                    $query->where('buyer_id', $buyerId);
                })
                ->when($dateFrom && $dateTo, function ($query) use ($dateFrom, $dateTo) {
                    $query->whereBetween('costing_date', [$dateFrom, $dateTo]);
                })
                ->when($internalRefNo, function ($query) use ($internalRefNo) {
                    $query->where('internal_ref', $internalRefNo);
                })
                ->when($fileNo, function ($query) use ($fileNo) {
                    $query->where('file_no', $fileNo);
                })
                ->when($year, function ($query) use ($year) {
                    $query->whereYear('created_at', $year);
                })
                ->get()
                ->makeHidden('costing');

            $formattedData = [];

            if ($label == 1 /*Style Wise*/) {
                foreach ($budgets as $budget) {
                    $formattedData[] = $this->formatStyleWiseData($budget, $label, $fabricActionStatus);
                }
            }

            if ($label == 2 /*PO Wise*/) {
                foreach ($budgets as $budget) {
                    foreach ($budget->order->purchaseOrders as $purchaseOrder) {
                        $formattedData[] = $this->formatPOWiseData($budget, $purchaseOrder, $label, $fabricActionStatus);
                    }
                }
            }
            $totalFabricBookings = FabricServiceBooking::all()->count();

            $dashboardOverview = [
                "Total Fabric Service Booking" => $totalFabricBookings
            ];

            return response()->json([
                'request' => $request->all(),
                'data' => $formattedData,
                'message' => '',
                'dashboardOverview' => $dashboardOverview,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'request' => $request->all(),
                'data' => null,
                'message' => $exception->getMessage(),
                'dashboardOverview' => $dashboardOverview,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function formatStyleWiseData($budget, $label, $fabricActionStatus = null)
    {
        return [
            'label' => $label,
            'budget_id' => $budget->id,
            'is_approve' => $budget->is_approve,
            'fabricActionStatus' => $fabricActionStatus,
            'unique_id' => $budget->job_no,
            'year' => Carbon::parse($budget->costing_date)->year,
            'factory' => $budget->factory->factory_name,
            'buyer' => $budget->buyer->name,
            'ref_no' => $budget->internal_ref,
            'style_name' => $budget->style_name,
            'job_qty' => $budget->job_qty,
            'file_no' => $budget->file_no,
            'po_no' => $budget->order->purchaseOrders->pluck('po_no')->implode(', '),
            'po_quantity' => $budget->order->purchaseOrders->pluck('po_quantity')->sum(),
            'shipment_date' => $budget->order->purchaseOrders->first()->ex_factory_date,
        ];
    }

    private function formatPOWiseData($budget, $purchaseOrder, $label, $fabricActionStatus = null)
    {
        return [
            'label' => $label,
            'budget_id' => $budget->id,
            'is_approve' => $budget->is_approve,
            'fabricActionStatus' => $fabricActionStatus,
            'unique_id' => $budget->job_no,
            'year' => Carbon::parse($budget->costing_date)->year,
            'factory' => $budget->factory->factory_name,
            'buyer' => $budget->buyer->name,
            'ref_no' => $budget->internal_ref,
            'style_name' => $budget->style_name,
            'job_qty' => $budget->job_qty,
            'file_no' => $budget->file_no,
            'po_no' => $purchaseOrder->po_no,
            'po_quantity' => $purchaseOrder->po_quantity,
            'shipment_date' => $purchaseOrder->ex_factory_date,
        ];
    }

    public function view($id)
    {
        $bookings = FabricServiceBookingReportService::mainBooking($id);
        $currency = Currency::query()->where('id', $bookings['currency'])->pluck('currency_name');
        $signature = ReportSignatureService::getSignatures("FABRIC SERVICE BOOKINGS VIEW", $bookings['buyer_id']);
        $termsConditions = TermsAndCondition::query()
            ->where('page_name', 'service_booking')
            ->get();
        return view('merchandising::booking.fabric-booking-service.view', compact('termsConditions', 'bookings', 'currency', 'signature'));
    }

    public function print($id)
    {
        $bookings = FabricServiceBookingReportService::mainBooking($id);
        $currency = Currency::query()->where('id', $bookings['currency'])->pluck('currency_name');
        $signature = ReportSignatureService::getSignatures("FABRIC SERVICE BOOKINGS VIEW", $bookings['buyer_id']);
        $termsConditions = TermsAndCondition::query()
            ->where('page_name', 'service_booking')
            ->get();
        return view('merchandising::booking.fabric-booking-service.print', compact('termsConditions', 'bookings', 'currency', 'signature'));
    }

    public function pdf($id)
    {
        $bookings = FabricServiceBookingReportService::mainBooking($id);
        $currency = Currency::query()->where('id', $bookings['currency'])->pluck('currency_name');
        $signature = ReportSignatureService::getSignatures("FABRIC SERVICE BOOKINGS VIEW", $bookings['buyer_id']);
        $termsConditions = TermsAndCondition::query()
            ->where('page_name', 'service_booking')
            ->get();

        if (\request()->has('without-price')) {
            $viewPage = 'pdf-without-price';
        }else{
            $viewPage = 'pdf';
        }

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView("merchandising::booking.fabric-booking-service.$viewPage", compact('termsConditions', 'bookings', 'currency', 'signature'))
            ->setPaper('a4')->setOrientation('landscape')->setOptions([
                'footer-html' => view('skeleton::pdf.footer', compact('signature')),
            ]);

        return $pdf->stream("{$id}_fabric_service_booking.pdf");
    }

    public function searchData(Request $request)
    {
        $paginateNumber = request('paginateNumber') ?? 15;

        $search = $request->get('search');
        $bookings = FabricServiceBooking::with('factory', 'buyer', 'supplier')
            ->where('booking_no', 'like', "%{$search}%")
            ->orWhere('booking_date', 'like', "%{$search}%")
            ->orWhere('delivery_date', 'like', "%{$search}%")
//            ->orWhere('fabric_source_name', 'like', "%{$search}%")
//            ->orWhere('level_name', 'like', "%{$search}%")
            ->orWhereHas('factory', function ($query) use ($search) {
                $query->where('factory_name', 'like', "%{$search}%");
            })
            ->orWhereHas('buyer', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate($paginateNumber);
        $searchedOrders = $bookings->total();
        $totalFabricBookings = FabricServiceBooking::all()->count();

        $dashboardOverview = [
            "Total Fabric Service Booking" => $totalFabricBookings
        ];


        return view('merchandising::booking.fabric-booking-service.index', compact('bookings', 'dashboardOverview', 'paginateNumber', 'searchedOrders'));
    }
}
