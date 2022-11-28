<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings;

use App\Http\Controllers\Controller;
use Excel;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoiceDetails;
use SkylarkSoft\GoRMG\Knitting\Models\FabricSalesOrder;
use SkylarkSoft\GoRMG\Knitting\Models\FabricSalesOrderDetail;
use SkylarkSoft\GoRMG\Merchandising\Actions\FabricBookingNotification;
use SkylarkSoft\GoRMG\Merchandising\Exports\FabricExcel;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetailsBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Services\Booking\Fabric\ListService;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\BudgetService;
use SkylarkSoft\GoRMG\Merchandising\Services\FileUploadRemoveService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricNature;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Knitting\Events\FabricBooking as DeleteFabricBooking;
use Throwable;
use SkylarkSoft\GoRMG\Merchandising\Services\ChartService\Consumers\MainFabricBookingChartService;

class FabricBookingController extends Controller
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
        $searchedBookings = 15;
        $fabricBookings = FabricBooking::query()
            ->with([
                'details.budget',
                'detailsBreakdown.budget.order',
                'factory:id,factory_name,factory_short_name',
                'buyer:id,name',
                'supplier:id,name',
                'budget:id,job_no,style_name'
            ])
            ->userWiseBuyerFilter()
            ->factoryWiseFilter()
            ->orderBy('id', 'desc')
            ->when(request('type') == 'Approved', function ($query) {
                $query->where('is_approve', 1);
            })
            ->when(request('type') == 'UnApproved', function ($query) {
                $query->where('is_approve', 0)->orWhereNull('is_approve');
            })
            ->paginate($paginateNumber);

        $chartService = new MainFabricBookingChartService();
        $dashboardOverview = $chartService->dashboardOverview();

        return view('merchandising::fabric-bookings.index', compact('fabricBookings', 'dashboardOverview', 'chartService', 'paginateNumber', 'searchedBookings'));
    }

    public function createOrUpdate()
    {
        return view('merchandising::fabric-bookings/create_update');
    }

    public function loadFactories(): JsonResponse
    {
        return response()->json(Factory::query()->userWiseFactories()->get(), Response::HTTP_OK);
    }

    public function loadCommonData($factoryId): JsonResponse
    {
        try {
            $data['buyers'] = Buyer::query()
                ->filterWithAssociateFactory('buyerWiseFactories', $factoryId)
                ->select('id', 'name', 'short_name', 'factory_id')
                ->get();
            $data['suppliers'] = Supplier::query()
                ->withoutGlobalScope('factoryId')
                ->filterWithAssociateFactory('supplierWiseFactories', $factoryId)
                ->select('id', 'name', 'short_name', 'factory_id')
                ->get();
            $data['currencies'] = Currency::all();
            $data['fabric_natures'] = FabricNature::all();
            $data['uoms'] = BudgetService::uoms();

            return response()->json([
                'data' => $data,
                'message' => 'All Related Data',
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'data' => null,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'factory_id' => 'required',
            'buyer_id' => 'required',
            'booking_date' => 'required',
            'fabric_source' => 'required',
            'delivery_date' => 'required',
            'currency_id' => 'required',
            'source' => 'required',
            'level' => 'required',
            'pay_mode' => 'required',
            'supplier_id' => 'required',
        ]);

        try {
            DB::beginTransaction();
            if ($request->get('id')) {
                $booking = FabricBooking::query()->findOrFail($request->get('id'));
                $booking->update($request->except('attachment'));
                FabricBookingNotification::send($booking);
                $message = 'Successfully Updated';
            } else {
                $message = 'Successfully Created';
                $booking = FabricBooking::create($request->except('attachment'));
            }
            if ($request->get('attachment') &&
                strpos($request->get('attachment'), 'image') !== false &&
                strpos($request->get('attachment'), 'base64') !== false) {
                $image_path = FileUploadRemoveService::fileUpload('bookings', $request->get('attachment'), 'image');
                if ($request->get('id') && Storage::disk('public')->exists($booking['attachment'])) {
                    Storage::delete($booking['attachment']);
                }
                $booking->update(['attachment' => $image_path]);
            }
            DB::commit();

            return response()->json([
                'booking' => $booking,
                'message' => $message,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'data' => null,
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function get($id): JsonResponse
    {
        try {
            $fabricBooking = FabricBooking::query()->findOrFail($id);

            return response()->json([
                'data' => $fabricBooking,
                'message' => 'Fabric Booking Data',
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
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
            'type' => 'main-fabric',
        ])->get();

        if (!count($proformaInvoiceStatus)) {
            try {
                FabricBooking::find($id)->delete();
                Session::flash('error', 'Data Deleted Successfully');

                return redirect('/fabric-bookings');
            } catch (Exception $exception) {
                return redirect('/fabric-bookings');
            }
        } else {
            Session::flash('error', 'Can not be deleted! This work-order already attached in PI');

            return redirect('/fabric-bookings');
        }
    }

    public function getList($id): JsonResponse
    {
        $dataFabricDetails = FabricBookingDetailsBreakdown::query()
            ->with('budget:id,job_no,style_name')
            ->where('booking_id', $id)
            ->get();

        $pi_details = ProformaInvoiceDetails::where([
            'booking_id' => $id,
            'type' => 'main-fabric',
        ])->get()->pluck('booking_details_id');

        $data = [
            'data' => ListService::getList($dataFabricDetails),
            'pi_details' => $pi_details,
        ];

        return response()->json($data, Response::HTTP_OK);
    }

    public function deleteList(Request $request): JsonResponse
    {
        if ($request->has('ids') && $request->get('ids')[0]) {
            try {
                $ids = $request->get('ids');
                $booking = FabricBookingDetailsBreakdown::query()->with('booking')->findOrFail($ids[0]);
                $proformaInvoiceStatus = ProformaInvoiceDetails::query()->where([
                    'booking_id' => $booking->booking_id,
                    'type' => 'main-fabric',
                ])->exists();

                if ($proformaInvoiceStatus) {
                    return response()->json('Already attached in proforma invoice', Response::HTTP_INTERNAL_SERVER_ERROR);
                }

                $fabricSalesOrder = FabricSalesOrder::query()->where('booking_no', $booking->booking->unique_id)->exists();
                if ($fabricSalesOrder) {
                    return response()->json('Already attached in fabric sales order', Response::HTTP_INTERNAL_SERVER_ERROR);
                }

                FabricBookingDetailsBreakdown::query()->where('id', $ids[0])->delete();
                return response()->json('Deleted Successfully', Response::HTTP_OK);
            } catch (Exception $exception) {
                return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
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
        $fabricBookings = FabricBooking::with('factory', 'buyer')
            ->when(request()->query('from_date') && request()->query('to_date'), function ($query) {
                $query->whereBetween('booking_date', [request()->query('from_date'), request()->query('to_date')]);
            })
            ->when($search, function ($query) use ($search, $label, $fabric_source) {
                $query->where('unique_id', 'like', '%' . $search . '%')
                    ->orWhere('fabric_source', $fabric_source)
                    ->orWhere('booking_date', 'like', '%' . $search . '%')
                    ->orWhere('delivery_date', 'like', '%' . $search . '%')
                    ->orWhere('level', $label)
                    ->orWhereHas('factory', function ($query) use ($search) {
                        return $query->where('factory_name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('buyer', function ($query) use ($search) {
                        return $query->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('detailsBreakdown.budget', function ($query) use ($search) {
                        return $query->where('style_name', 'like', '%' . $search . '%')
                            ->orWhere('job_no', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('detailsBreakdown', function ($query) use ($search) {
                        return $query->where('po_no', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('detailsBreakdown.budget.order', function ($query) use ($search) {
                        return $query->where('reference_no', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('supplier', function ($query) use ($search) {
                        return $query->where('name', 'like', '%' . $search . '%');
                    });
            })
            ->orderBy('id', $sort)
            ->paginate($paginateNumber);
        $searchedBookings = $fabricBookings->total();

        $chartService = new MainFabricBookingChartService();
        $dashboardOverview = $chartService->dashboardOverview();
        return view('merchandising::fabric-bookings/index', compact(
            'fabricBookings',
            'chartService',
            'search',
            'dashboardOverview',
            'paginateNumber',
            'searchedBookings'));
    }

    public function moqQty(Request $request)
    {
        $search = $request->get('search');

        $fabricBookings = FabricBooking::with([
            'details.budget',
            'factory:id,factory_name',
            'buyer:id,name',
            'budget:id,job_no,style_name',
            'detailsBreakdown'])
            ->when($search, function ($query) use ($search) {
                $query->where('unique_id', 'like', '%' . $search . '%');
            })
            ->orWhere('fabric_source', 'like', '%' . $search . '%')
            ->orWhere('booking_date', 'like', '%' . $search . '%')
            ->orWhere('delivery_date', 'like', '%' . $search . '%')
            ->orWhere('level', 'like', '%' . $search . '%')
            ->orWhereHas('factory', function ($query) use ($search) {
                $query->where('factory_name', 'like', '%' . $search . '%');
            })
            ->orWhereHas('buyer', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orWhereHas('budget', function ($query) use ($search) {
                $query->where('style_name', 'like', '%' . $search . '%');
                $query->orWhere('job_no', 'like', '%' . $search . '%');
            })
            ->orWhereHas('detailsBreakdown', function ($query) use ($search) {
                $query->where('po_no', 'like', '%' . $search . '%');
            })
            ->withSum('detailsBreakdown', 'moq_qty')
            ->userWiseBuyerFilter()
            ->factoryWiseFilter()
            ->orderBy('id', 'desc')
            ->get();

        $fabricBookings = $fabricBookings->whereNotNull('details_breakdown_sum_moq_qty')->paginate();

        return view('merchandising::fabric-bookings.fabric-booking-moq-list', compact('fabricBookings'));
    }

    public function loadPo($id): JsonResponse
    {
        $purchaseOrders = FabricBooking::query()->with('detailsBreakdown')->findOrFail($id)['po_no'];
        $purchaseOrders = explode(',', $purchaseOrders);
        return response()->json($purchaseOrders);
    }


    public function FabricListExcelAll(Request $request)
    {
        $search = $request->get('search');
        $sort = request('sort') ?? 'desc';
        $label = strtolower($search) == "job label" ? 1 : (strtolower($search) == "po label" ? 2 : $search);
        $fabric_source = array_search(ucfirst($search), self::FABRIC_SOURCE) ?: $search;

        return Excel::download(new FabricExcel($search, $sort, $label, $fabric_source), 'fabric-list-all.xlsx');
    }
}
