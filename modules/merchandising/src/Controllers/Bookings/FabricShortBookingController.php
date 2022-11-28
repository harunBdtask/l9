<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoiceDetails;
use SkylarkSoft\GoRMG\Knitting\Events\FabricBooking as DeleteFabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Actions\ShortFabricBookingNotification;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortFabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortFabricBookingDetailsBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Requests\ShortFabricBookingRequest;
use SkylarkSoft\GoRMG\Merchandising\Services\Booking\Fabric\BodyPartsService;
use SkylarkSoft\GoRMG\Merchandising\Services\Booking\Fabric\ListService;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\BudgetService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricNature;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\Merchandising\Services\ChartService\Consumers\ShortFabricBookingChartService;
use Throwable;

class FabricShortBookingController extends Controller
{
    private const FABRIC_SOURCE = [
        1 => 'Production',
        2 => 'Purchase',
        3 => 'Buyer',
        4 => 'Supplier Stock'
    ];

    public function index()
    {
        $paginateNumber = request('paginateNumber') ?? 15;
        $searchedOrders = 15;
        $fabricBookings = ShortFabricBooking::with('factory', 'buyer', 'supplier:id,name')
            ->userWiseBuyerFilter()
            ->factoryWiseFilter()
            ->orderBy('id', 'desc')->paginate($paginateNumber);


            $chartService= new ShortFabricBookingChartService();
            $dashboardOverview= $chartService->dashboardOverview();



        return view('merchandising::fabric-short-bookings/index', compact('fabricBookings','dashboardOverview','chartService','paginateNumber','searchedOrders'));
    }

    public function createOrUpdate()
    {
        return view('merchandising::fabric-short-bookings/create_update');
    }

    public function loadFactories(): JsonResponse
    {
        return response()->json(Factory::all(), Response::HTTP_OK);
    }

    public function loadCommonData($factoryId): JsonResponse
    {
        try {
            $data['buyers'] = Buyer::where('factory_id', $factoryId)->get();
            $data['suppliers'] = Supplier::where('factory_id', $factoryId)->get();
            $data['currencies'] = Currency::all();
            $data['fabric_natures'] = FabricNature::all();
            $data['uoms'] = BudgetService::uoms();

            return response()->json([
                'data' => $data,
                'message' => 'All Related Data',
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json([
                'data' => null,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws Throwable
     */
    public function store(ShortFabricBookingRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            if ($request->get('id')) {
                $booking = ShortFabricBooking::findOrFail($request->get('id'));
                $booking->update($request->all());
                ShortFabricBookingNotification::send($booking);
                $msg = 'Successfully Created';
            } else {
                $requestAttributes = $request->all();
                $booking = ShortFabricBooking::create($requestAttributes);
                $msg = 'Successfully Updated';
            }
            DB::commit();
            return response()->json(['booking' => $booking, 'message' => $msg], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json(['data' => null, 'message' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function get($id): JsonResponse
    {
        try {
            $fabricBooking = ShortFabricBooking::findOrFail($id);

            return response()->json([
                'data' => $fabricBooking,
                'message' => 'Fabric Booking Data',
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json([
                'data' => null,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete($id)
    {
        $proformaInvoiceStatus = ProformaInvoiceDetails::where([
            'booking_id' => $id,
            'type' => 'short-fabric',
        ])->get();

        if (!count($proformaInvoiceStatus)) {
            try {
                ShortFabricBooking::find($id)->delete();
                Session::flash('error', 'Data Deleted Successfully');

                return redirect('/short-fabric-bookings');
            } catch (\Exception $exception) {
                return redirect('/short-fabric-bookings');
            }
        } else {
            Session::flash('error', 'Can not be deleted! This work-order already attached in PI');

            return redirect('/short-fabric-bookings');
        }
    }

    public function getList($id): JsonResponse
    {
        $dataShortFabricDetails = ShortFabricBookingDetailsBreakdown::query()->where('short_booking_id', $id)->get();

        $pi_details = ProformaInvoiceDetails::query()->where([
            'booking_id' => $id,
            'type' => 'short-fabric',
        ])->get()->pluck('booking_details_id');

        $data = [
            'data' => ListService::getList($dataShortFabricDetails),
            'pi_details' => $pi_details,
        ];

        return response()->json($data, Response::HTTP_OK);
    }

    public function deleteList(Request $request): JsonResponse
    {
        if ($request->has('ids')) {
            $ids = $request->get('ids');
            if (isset($ids[0])) {
                $booking = ShortFabricBookingDetailsBreakdown::findOrFail($ids[0]);
                if ($booking) {
                    $proformaInvoiceStatus = ProformaInvoiceDetails::where([
                        'booking_id' => $booking->booking_id,
                        'type' => 'main-fabric',
                    ])->get();
                } else {
                    $proformaInvoiceStatus = [];
                }

                if (!count($proformaInvoiceStatus)) {
                    try {
                        ShortFabricBookingDetailsBreakdown::query()->whereIn('id', $ids)->delete();
                        DeleteFabricBooking::dispatch($ids);

                        return response()->json('Deleted Successfully', Response::HTTP_OK);
                    } catch (\Exception $exception) {
                        return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
                    }
                } else {
                    return response()->json(['already attached in proforma invoice'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
        }

        return response()->json('Something Went Wrong', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $sort = request('sort') ?? 'desc';
        $label = strtolower($search) == "job label" ? 1 : (strtolower($search) == "po label" ? 2 : $search);
        $fabric_source = array_search(ucfirst($search), self::FABRIC_SOURCE) ?: $search;
        $paginateNumber = request('paginateNumber') ?? 15;
        $fabricBookings = ShortFabricBooking::with('factory', 'buyer')
            ->where('unique_id', 'like', '%' . $search . '%')
            ->orWhere('fabric_source', $fabric_source)
            ->orWhere('booking_date', 'like', '%' . $search . '%')
            ->orWhere('delivery_date', 'like', '%' . $search . '%')
            ->orWhere('level', $label)
            ->orWhereHas('factory', function ($query) use ($search) {
                $query->where('factory_name', 'like', '%' . $search . '%');
            })
            ->orWhereHas('buyer', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orWhereHas('supplier', function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('id', $sort)
            ->paginate($paginateNumber);
        $totalshrtfbrbooking = ShortFabricBooking::all()->count();
        $searchedOrders = $fabricBookings->total();

        $dashboardOverview = [
            "Total Short Fabric Booking" => $totalshrtfbrbooking
        ];

        return view('merchandising::fabric-short-bookings/index', compact('fabricBookings', 'search','dashboardOverview','paginateNumber','searchedOrders'));
    }

    public function bodyParts(Request $request): JsonResponse
    {
        try {
            $bookingJobNos = ShortFabricBookingDetailsBreakdown::query()
                ->where('short_booking_id', $request->get('id'))
                ->pluck('job_no')
                ->unique();

            $data = BodyPartsService::getBodyParts($bookingJobNos);

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
