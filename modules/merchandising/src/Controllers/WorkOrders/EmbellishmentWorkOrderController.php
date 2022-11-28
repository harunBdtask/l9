<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\WorkOrders;

use App\Http\Controllers\Controller;
use Excel;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use NumberFormatter;
use PDF;
use SkylarkSoft\GoRMG\Merchandising\Actions\EmbellishmentOrderFilterFormat;
use SkylarkSoft\GoRMG\Merchandising\Exports\EmbellishmentOrderExcel;
use SkylarkSoft\GoRMG\Merchandising\Models\WorkOrders\EmbellishmentBookingItemDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\WorkOrders\EmbellishmentWorkOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\WorkOrders\EmbellishmentWorkOrderDetails;
use SkylarkSoft\GoRMG\Merchandising\Services\Booking\QtyWiseFormatter;
use SkylarkSoft\GoRMG\Merchandising\Services\Embellishment\EmbellishmentWorkOrderReportService;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class EmbellishmentWorkOrderController extends Controller
{
    public function index()
    {
        $paginateNumber = request('paginateNumber') ?? 15;
        $searchedOrders = 15;

        $workOrders = EmbellishmentWorkOrder::query()
            ->with('buyer:id,name', 'factory:id,group_name,factory_name,factory_short_name', 'supplier:id,name',
                'bookingDetails')
            ->latest()
            ->paginate($paginateNumber);
        $totalOrders = EmbellishmentWorkOrder::all()->count();
        $dashboardOverview = [
            "Total Embellished Orders" => $totalOrders
        ];
        return view('merchandising::work-orders.embellishment-index', compact('workOrders', 'dashboardOverview', 'paginateNumber', 'searchedOrders'));
    }

    public function orderListExcelAll(Request $request, EmbellishmentOrderFilterFormat $orderFilterFormat)
    {
        $search = $request->get('search');
        $sort = $request->get('sort') ?? 'DESC';
        $workOrders = $orderFilterFormat->handleAll($search, $sort);
        return Excel::download(new EmbellishmentOrderExcel($workOrders), 'embellishment-list-all.xlsx');
    }

    public function orderListExcelList(Request $request, EmbellishmentOrderFilterFormat $orderFilterFormat)
    {
        $search = $request->get('search');
        $sort = $request->get('sort') ?? 'DESC';
        $page = $request->get('page');
        $paginateNumber = $request->get('paginateNumber');
        $workOrders = $orderFilterFormat->handle($search, $sort, $page, $paginateNumber);
        return Excel::download(new EmbellishmentOrderExcel($workOrders), 'embellishment-list-all.xlsx');
    }

    public function create()
    {
        return view('merchandising::work-orders.embellishment');
    }

    public function searchData()
    {
        $paginateNumber = request('paginateNumber') ?? 15;
        $q = request('search');

        $workOrders = EmbellishmentWorkOrder::query()
            ->with('buyer:id,name', 'factory:id,group_name,factory_name', 'supplier:id,name')
            ->when($q, function ($query) use ($q) {
                $query->whereHas('buyer', function ($query) use ($q) {
                    return $query->where('name', 'LIKE', $q);
                })
                    ->orWhereHas('factory', function ($query) use ($q) {
                        return $query->where('factory_name', 'LIKE', $q);
                    })
                    ->orWhereHas('supplier', function ($query) use ($q) {
                        return $query->where('name', 'LIKE', $q);
                    })
                    ->orWhereHas('bookingDetails', function ($query) use ($q) {
                        return $query->where('budget_unique_id', 'LIKE', "%$q%")
                            ->orWhere('style', 'LIKE', "%$q%");
                    });
            })
            ->when(request()->query('from_date') && request()->query('to_date'), function ($query) {
                $query->whereBetween('created_at', [request()->query('from_date'), request()->query('to_date')]);
            })
            ->latest()
            ->paginate($paginateNumber);
        $searchedOrders = $workOrders->total();

        $totalOrders = EmbellishmentWorkOrder::all()->count();

        $dashboardOverview = [
            "Total Embellished Orders" => $totalOrders
        ];

        return view('merchandising::work-orders.embellishment-index', compact('workOrders', 'dashboardOverview', 'paginateNumber', 'searchedOrders'));
    }

    public function store(Request $request): JsonResponse
    {
        $this->validateRequest($request);

        $workOrder = EmbellishmentWorkOrder::firstOrNew(['id' => $request->id]);

        try {
            $workOrder->factory_id = $request->factory_id;
            $workOrder->location = $request->location;
            $workOrder->buyer_id = $request->buyer_id;
            $workOrder->booking_date = $request->booking_date;
            $workOrder->delivery_date = $request->delivery_date;
            $workOrder->supplier_id = $request->supplier_id;
            $workOrder->pay_mode = $request->pay_mode;
            $workOrder->source = $request->source;
            $workOrder->exchange_rate = $request->exchange_rate;
            $workOrder->currency = $request->currency;
            $workOrder->attention = $request->attention;
            $workOrder->remarks = $request->remarks;
            $workOrder->is_short = $request->is_short;
            $workOrder->ready_to_approve = $request->ready_to_approve;
            $workOrder->unapproved_request = $request->unapproved_request;
            $workOrder->save();

            return response()->json(['message' => 'Successfully Saved!', 'booking' => $workOrder], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!', 'error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    private function validateRequest(Request $request)
    {
        $request->validate([
            'factory_id' => 'required',
            'location' => 'required',
            'buyer_id' => 'required',
            'booking_date' => 'required',
            'supplier_id' => 'required',
            'pay_mode' => 'required',
            'source' => 'required',
            'is_short' => 'required',
        ], [
            'required' => 'Required',
        ]);
    }

    public function show($id): JsonResponse
    {
        $workOrder = EmbellishmentWorkOrder::findOrFail($id);

        return response()->json($workOrder);
    }

    /**
     * @param $id
     * @return JsonResponse
     * @throws Throwable
     */
    public function delete($id): JsonResponse
    {
        DB::beginTransaction();
        $workOrder = EmbellishmentWorkOrder::findOrFail($id);
        EmbellishmentBookingItemDetails::where([
            'embellishment_work_order_id' => $id,
        ])->delete();
        EmbellishmentWorkOrderDetails::where([
            'embellishment_work_order_id' => $id,
        ])->delete();
        DB::commit();
        $workOrder->delete();

        return response()->json(['message' => 'Deleted']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function saveBookingDetails(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $embellishmentBooking = EmbellishmentWorkOrder::findOrFail($request->get('id'));
            $embellishmentBookingDetails = [];
            $bookingQuantities = [];
            foreach ($request->get('details') as $details) {
                if (!isset($details['name_id'])) {
                    continue;
                }
                $findBooking = EmbellishmentWorkOrderDetails::where([
                    'embellishment_id' => $details['name_id'],
                    'embellishment_work_order_id' => $request->get('id'),
                    'budget_unique_id' => $details['budget']['job_no'],
                ])->first();

                if (!$findBooking) {
                    $formatter = new QtyWiseFormatter();
                    $data = [
                        'embellishment_work_order_id' => $request->get('id'),
                        'budget_unique_id' => $details['budget']['job_no'],
                        'po_no' => $details['po'],
                        'style' => $details['budget']['style_name'] ?? '',
                        'embellishment_id' => $details['name_id'],
                        'embellishment_type_id' => $details['type_id'],
                        'body_part_id' => $details['body_part_id'] ?? '',
//                        'total_qty' => $details['breakdown']['total_qty_sum'] ?? 0,
                        'total_qty' => isset($details['breakdown']['details']) ? collect($details['breakdown']['details'])->sum('total_qty') : 0,
//                        'total_amount' => $details['breakdown']['total_amount_sum'] ?? 0,
                        'total_amount' => isset($details['breakdown']['details']) ? collect($details['breakdown']['details'])->sum('total_amount') : 0,
                        'breakdown' => $details['breakdown'] ?? '',
                        'created_by' => auth()->user()->id,
                        'created_at' => now(),
                    ];
                    $item['breakdown'] = $data['breakdown']['details'];

                    $calculations = collect($formatter->format((object)$item, 'embellishment_booking'))->first();

                    $data = array_merge($data, [
                        'work_order_qty' => $calculations['wo_total_qty'],
                        'work_order_rate' => $calculations['rate'],
                        'work_order_amount' => $calculations['amount'],
                    ]);

                    $bookingQuantities[] = [
                        'embellishment_work_order_id' => $request->get('id'),
                        'budget_unique_id' => $data['budget_unique_id'],
                        'item_id' => $data['embellishment_id'],
                        'item_type_id' => $data['embellishment_type_id'],
                        'qty' => $calculations['wo_total_qty'],
                        'created_at' => now(),
                        'updated_at' => now(),
                        'factory_id' => factoryId(),
                    ];
                    $embellishmentBookingDetails[] = $data;
                }
            }
            $embellishmentBooking->bookingDetails()->createMany($embellishmentBookingDetails);
            $embellishmentBooking->bookingItemDetails()->createMany($bookingQuantities);
            DB::commit();
            $response = [
                'status' => Response::HTTP_CREATED,
                'message' => 'Bookings Saved Successfully',
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function bookingDetails($id): JsonResponse
    {
        try {
            $details = EmbellishmentWorkOrder::with([
                'buyer',
                'bookingDetails',
                'bookingDetails.embellishment',
                'bookingDetails.embellishmentType',
                'bookingDetails.bodyPart',
                'bookingDetails.budget.uom',
                'bookingDetails.budget.order',
                'bookingDetails.budget.costings' => function ($query) {
                    $query->whereIn('type', [
                        'embellishment_cost',
                        'wash_cost',
                    ]);
                },
            ])->findOrFail($id);
            $response = [
                'data' => $details,
                'status' => Response::HTTP_OK,
                'message' => 'Bookings Fetched Successfully',
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteBookingDetails($bookingId, $itemId, $itemTypeId): JsonResponse
    {
        try {
            DB::beginTransaction();
            EmbellishmentBookingItemDetails::where([
                'embellishment_work_order_id' => $bookingId,
                'item_id' => $itemId,
                'item_type_id' => $itemTypeId,
            ])->delete();
            EmbellishmentWorkOrderDetails::where([
                'embellishment_work_order_id' => $bookingId,
                'embellishment_id' => $itemId,
                'embellishment_type_id' => $itemTypeId,
            ])->delete();
            DB::commit();
            $response = [
                'status' => Response::HTTP_OK,
                'message' => 'Deleted',
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (Throwable $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function view($id)
    {
        $numberInWord = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        $workOrder = EmbellishmentWorkOrderReportService::mainData($id);

        $gmsTotalAmount = $sizeTotalAmount = $contrastTotalAmount = $colorTotalAmount = 0;
        $gmsEmblType = $sizeEmblType = $contrastEmblType = $colorEmblType = [];
        $gmsProcess = $sizeProcess = $contrastProcess = $colorProcess = [];

        if (isset($workOrder) && count($workOrder->gmtsColorWiseWorkOrder) > 0) {
            $totalWoQty = 0;
            foreach ($workOrder->gmtsColorWiseWorkOrder as $index => $item) {
                $amount = ($item['wo_total_qty'] * $item['rate']);
                $totalWoQty += $item['wo_total_qty'];
                $gmsTotalAmount += $amount;
                array_push($gmsEmblType, $item['embl_name']);
                array_push($gmsProcess, $item['embl_type']);
            }
        }

        if (isset($workOrder) && count($workOrder->colorSizeSensitivity) > 0) {
            $totalWoQty = 0;
            foreach ($workOrder->colorSizeSensitivity as $index => $item) {
                $amount = ($item['wo_total_qty'] * $item['rate']);
                $totalWoQty += $item['wo_total_qty'];
                $colorTotalAmount += $amount;
                array_push($colorEmblType, $item['embl_name']);
                array_push($colorProcess, $item['embl_type']);
            }
        }

        if (isset($workOrder) && count($workOrder->contrastColorWiseWorkOrder) > 0) {
            $totalWoQty = 0;
            foreach ($workOrder->contrastColorWiseWorkOrder as $index => $item) {
                $amount = ($item['wo_total_qty'] * $item['rate']);
                $totalWoQty += $item['wo_total_qty'];
                $contrastTotalAmount += $amount;
                array_push($contrastEmblType, $item['embl_name']);
                array_push($contrastProcess, $item['embl_type']);
            }
        }

        if (isset($workOrder) && count($workOrder->sizeSensitivity) > 0) {
            $totalWoQty = 0;
            foreach ($workOrder->sizeSensitivity as $index => $item) {
                $amount = ($item['wo_total_qty'] * $item['rate']);
                $totalWoQty += $item['wo_total_qty'];
                $sizeTotalAmount += $amount;
                array_push($sizeEmblType, $item['embl_name']);
                array_push($sizeProcess, $item['embl_type']);
            }
        }

        $workOrder['total'] = $gmsTotalAmount + $sizeTotalAmount + $contrastTotalAmount + $colorTotalAmount;
        $workOrder['emblType'] = implode(', ', collect(array_merge($gmsEmblType, $sizeEmblType, $contrastEmblType, $colorEmblType))->unique()->toArray());
        $workOrder['process'] = implode(', ', collect(array_merge($gmsProcess, $sizeProcess, $contrastProcess, $colorProcess))->unique()->toArray());
        $value = sprintf("%.4f", $workOrder['total']);
        $workOrder['totalInWords'] = ucwords($numberInWord->format($value));

        if ($workOrder->bookingDetails) {
            $workOrder['costing_per'] = $workOrder->bookingDetails->first()->budget->costing_per ?? 1;
        }

        $signature = ReportSignatureService::getApprovalSignature(EmbellishmentWorkOrder::class, $id);
        return view('merchandising::work-orders.reports.view', compact('workOrder', 'signature'));
    }

    public function pdf($id)
    {
        $numberInWord = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        $workOrder = EmbellishmentWorkOrderReportService::mainData($id);
        $signature = ReportSignatureService::getApprovalSignature(EmbellishmentWorkOrder::class, $id);
        $gmsTotalAmount = $sizeTotalAmount = $contrastTotalAmount = $colorTotalAmount = 0;
        $gmsEmblType = $sizeEmblType = $contrastEmblType = $colorEmblType = [];
        $gmsProcess = $sizeProcess = $contrastProcess = $colorProcess = [];

        if (isset($workOrder) && count($workOrder->gmtsColorWiseWorkOrder) > 0) {
            $totalWoQty = 0;
            foreach ($workOrder->gmtsColorWiseWorkOrder as $index => $item) {
                $amount = ($item['wo_total_qty'] * $item['rate']);
                $totalWoQty += $item['wo_total_qty'];
                $gmsTotalAmount += $amount;
                array_push($gmsEmblType, $item['embl_name']);
                array_push($gmsProcess, $item['embl_type']);
            }
        }

        if (isset($workOrder) && count($workOrder->colorSizeSensitivity) > 0) {
            $totalWoQty = 0;
            foreach ($workOrder->colorSizeSensitivity as $index => $item) {
                $amount = ($item['wo_total_qty'] * $item['rate']);
                $totalWoQty += $item['wo_total_qty'];
                $colorTotalAmount += $amount;
                array_push($colorEmblType, $item['embl_name']);
                array_push($colorProcess, $item['embl_type']);
            }
        }

        if (isset($workOrder) && count($workOrder->contrastColorWiseWorkOrder) > 0) {
            $totalWoQty = 0;
            foreach ($workOrder->contrastColorWiseWorkOrder as $index => $item) {
                $amount = ($item['wo_total_qty'] * $item['rate']);
                $totalWoQty += $item['wo_total_qty'];
                $contrastTotalAmount += $amount;
                array_push($contrastEmblType, $item['embl_name']);
                array_push($contrastProcess, $item['embl_type']);
            }
        }

        if (isset($workOrder) && count($workOrder->sizeSensitivity) > 0) {
            $totalWoQty = 0;
            foreach ($workOrder->sizeSensitivity as $index => $item) {
                $amount = ($item['wo_total_qty'] * $item['rate']);
                $totalWoQty += $item['wo_total_qty'];
                $sizeTotalAmount += $amount;
                array_push($sizeEmblType, $item['embl_name']);
                array_push($sizeProcess, $item['embl_type']);
            }
        }

        $workOrder['total'] = $gmsTotalAmount + $sizeTotalAmount + $contrastTotalAmount + $colorTotalAmount;
        $workOrder['emblType'] = implode(', ', collect(array_merge($gmsEmblType, $sizeEmblType, $contrastEmblType, $colorEmblType))->unique()->toArray());
        $workOrder['process'] = implode(', ', collect(array_merge($gmsProcess, $sizeProcess, $contrastProcess, $colorProcess))->unique()->toArray());
        $value = sprintf("%.4f", $workOrder['total']);

        $workOrder['totalInWords'] = ucwords($numberInWord->format($value));

        if ($workOrder->bookingDetails) {
            $workOrder['costing_per'] = $workOrder->bookingDetails->first()->budget->costing_per ?? 1;
        }

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView("merchandising::work-orders.reports.pdf", compact('workOrder', 'signature'))
            ->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer', compact('signature')),
            ]);

        return $pdf->stream("{$id}_embellishment.pdf");
    }

    public function print($id)
    {
        $workOrder = EmbellishmentWorkOrderReportService::mainData($id);
        $signature = ReportSignatureService::getApprovalSignature(EmbellishmentWorkOrder::class, $id);
        return view('merchandising::work-orders.reports.print', compact('workOrder', 'signature'));
    }
}
