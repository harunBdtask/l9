<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings;

use App\Http\Controllers\Controller;
use Excel;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use NumberFormatter;
use PDF;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoiceDetails;
use SkylarkSoft\GoRMG\Merchandising\Actions\TrimsBookingNotification;
use SkylarkSoft\GoRMG\Merchandising\Exports\TrimsBookingExcel;
use SkylarkSoft\GoRMG\Merchandising\Exports\TrimsExcel;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBookingDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBookingItemDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Services\Booking\QtyWiseFormatter;
use SkylarkSoft\GoRMG\Merchandising\Services\Booking\TrimsBookingsReportService;
use SkylarkSoft\GoRMG\Merchandising\Services\Booking\TrimsBookingWoWiseReportService;
use SkylarkSoft\GoRMG\Merchandising\Services\ChartService\Consumers\MainTrimsBookingChartService;
use SkylarkSoft\GoRMG\Merchandising\Services\FileUploadRemoveService;
use SkylarkSoft\GoRMG\Merchandising\Services\PurchaseOrder\POBookingStatusService;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;
use SkylarkSoft\GoRMG\SystemSettings\Models\MerchandisingVariableSettings;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricComposition;
use SkylarkSoft\GoRMG\SystemSettings\Services\FieldsService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TrimsBookingController extends Controller
{
    public function index()
    {
        $paginateNumber = request('paginateNumber') ?? 15;
        $searchedTrimsBooking = 15;
        $trimsBookings = TrimsBooking::query()
            ->userWiseBuyerFilter()
            ->factoryWiseFilter()
            ->with('bookingDetails.budget.order', 'buyer:id,name', 'factory:id,group_name,factory_name,factory_short_name', 'supplier:id,name')
            ->when(request('type') == 'Approved Trims Booking', function ($query) {
                $query->where('is_approve', 1);
            })
            ->when(request('type') == 'UnApproved Trims Booking', function ($query) {
                $query->where('is_approve', 0)->orWhereNull('is_approve');
            })
            ->orderBy('id', 'desc')
            ->groupBy(['unique_id'])
            ->paginate($paginateNumber);


        $styles = TrimsBookingDetails::query()->pluck('style_name', 'style_name')->unique()->toArray();
        $buyers = Buyer::query()->get(['name']);

        $chartService = new MainTrimsBookingChartService();
        $dashboardOverview = $chartService->dashboardOverview();


        return view('merchandising::booking.trims-booking-index', compact(
            'trimsBookings',
            'chartService',
            'styles',
            'buyers',
            'dashboardOverview',
            'paginateNumber',
            'searchedTrimsBooking'));
    }

    public function search()
    {
        $bookingNo = request()->query('booking_no');
        $style = request()->query('style');
        $buyer = request()->query('buyer');

        $sort = request('sort') ?? 'desc';

        $paginateNumber = request('paginateNumber') ?? 15;

        $trimsBookings = TrimsBooking::query()
            ->with([
                'bookingDetails.budget',
                'buyer:id,name',
                'factory:id,group_name,factory_name',
                'supplier:id,name'
            ])
            ->when($bookingNo, function (Builder $query) use ($bookingNo) {
                $query->where('unique_id', $bookingNo);
            })
            ->when($buyer, function (Builder $query) use ($buyer) {
                $query->whereHas('buyer', function ($query) use ($buyer) {
                    return $query->where('name', 'LIKE', '%' . $buyer . '%');
                });
            })
            ->when($style, function (Builder $query) use ($style) {
                $query->whereHas('bookingDetails', function ($query) use ($style) {
                    return $query->where('style_name', $style);
                });
            })
            ->orderBy('id', $sort)
            ->paginate($paginateNumber);

        $styles = TrimsBookingDetails::query()->pluck('style_name', 'style_name')->unique()->toArray();
        $buyers = Buyer::query()->get(['name']);

        $searchedTrimsBooking = $trimsBookings->total();
        $chartService = new MainTrimsBookingChartService();
        $dashboardOverview = $chartService->dashboardOverview();

        return view('merchandising::booking.trims-booking-index', compact(
            'chartService',
            'trimsBookings',
            'styles',
            'buyers',
            'dashboardOverview',
            'paginateNumber',
            'searchedTrimsBooking'));
    }

    public function bookingMainPage()
    {
        return view('merchandising::booking.trims-booking');
    }

    public function factories()
    {
        $factories = Factory::all();

        return response()->json($factories);
    }

    public function store(Request $request)
    {
        $this->validateRequest($request);

        try {
            $booking = new TrimsBooking($request->all([
                'factory_id',
                'location',
                'buyer_id',
                'booking_date',
                'delivery_date',
                'supplier_id',
                'source',
                'booking_basis',
                'trims_type',
                'material_source',
                'pay_mode',
                'exchange_rate',
                'level',
                'currency',
                'attention',
                'remarks',
                'delivery_to',
                'ready_to_approve',
                'terms_condition',
            ]));

            $booking->save();

            return response()->json(['message' => 'Successfully Saved!', 'booking' => $booking], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!', 'error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(TrimsBooking $booking, Request $request)
    {
        $this->validateRequest($request);
        try {

            if ($booking->is_approve == 1) {
                $booking->fill($request->only(['un_approve_request']));
            } else {
                $booking->fill($request->all());
            }
            $booking->save();

            TrimsBookingNotification::send($booking);

            return response()->json(['message' => 'Successfully Updated!', 'booking' => $booking], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!', 'error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function delete(TrimsBooking $booking): JsonResponse
    {
        $proformaInvoiceStatus = ProformaInvoiceDetails::where([
            'booking_id' => $booking->id,
            'type' => 'main-trims',
        ])->get();

        if (!count($proformaInvoiceStatus)) {
            try {
                TrimsBookingItemDetails::query()
                    ->where([
                        'booking_id' => $booking->id,
                    ])->delete();
                $booking->details()->delete();
                $booking->delete();

                return response()->json(['message' => 'Successfully Deleted!'], Response::HTTP_OK);
            } catch (Exception $e) {
                return response()->json(['message' => 'Something Went Wrong!', 'error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response()->json(['message' => 'already used in proforma invoice'], Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    /**
     * @param Request $request
     */
    public function validateRequest(Request $request)
    {
        $request->validate([
            'factory_id' => 'required',
            'location' => 'required',
            'buyer_id' => 'required',
            'booking_date' => 'required',
            'supplier_id' => 'required',
            'booking_basis' => 'required',
            'material_source' => 'required',
            'pay_mode' => 'required',
            'source' => 'required',
            'level' => 'required',
        ], [
            'required' => 'Required',
        ]);
    }

    public function show($id)
    {
        $booking = TrimsBooking::findOrFail($id);

        return response()->json($booking);
    }

    private function getTrimsType($typeId)
    {
        if ($typeId == 1) {
            return 'Sewing Trims';
        }
        if ($typeId == 2) {
            return 'Finishing Trims';
        }

        return null;
    }

    private function getTrimsIds($trimsType)
    {
        if ($trimsType) {
            return ItemGroup::where('trims_type', $trimsType)->pluck('id');
        }

        return ItemGroup::pluck('id');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function trimsBookingSearch(Request $request): JsonResponse
    {
        try {
            $booking = TrimsBooking::find($request->get('booking_id'));
            $trimsType = $this->getTrimsType($booking->trims_type);
            $trimsIds = $this->getTrimsIds($trimsType);
            $style_name = $request->get('style_name');
            $factory_id = $request->get('factory_id');
            $buyer_id = $request->get('buyer_id');
            $level = $request->get('level');
            $unique_id = $request->get('unique_id');
            $po_no = $request->get('po_no');
            $from_date = $request->get('from_date');
            $to_date = $request->get('to_date');

            $trimsActionStatus = MerchandisingVariableSettings::query()->where(['factory_id' => $factory_id, 'buyer_id' => $buyer_id])->first();
            $trimsActionStatus = isset($trimsActionStatus) ? $trimsActionStatus['variables_details']['budget_approval_required_for_booking']['trims_part'] : null;

            $trims_costings = Budget::query()
                ->with(['order.purchaseOrders' => function ($query) use ($po_no) {
                    $query->when($po_no, function ($query) use ($po_no) {
                        return $query->where('purchase_orders.po_no', $po_no);
                    });
                }, 'trimCosting'])
                ->when($unique_id, function ($query) use ($unique_id) {
                    $query->where('job_no', 'like', '%' . $unique_id . '%');
                })
                ->when($style_name, function ($query) use ($style_name) {
                    $query->where('style_name', $style_name);
                })
                ->when($factory_id, function ($query) use ($factory_id) {
                    $query->where('factory_id', $factory_id);
                })
                ->when($buyer_id, function ($query) use ($buyer_id) {
                    $query->where('buyer_id', $buyer_id);
                })
                ->when($from_date && $to_date, function ($query) use ($from_date, $to_date) {
                    $query->whereBetween('costing_date', [$from_date, $to_date]);
                })
                ->get();

            $trims_costings = $trims_costings->flatMap(function ($trims_costing) use ($level, $trimsActionStatus) {
                if ($level == 1) {
                    $po = $trims_costing->order->purchaseOrders->pluck('po_no')->unique()->implode(',');
                    if (isset($trims_costing->trimCosting->details['details'])) {
                        return $this->formatSearchValue($trims_costing->trimCosting->details['details'], $trims_costing, $po, $level, $trimsActionStatus);
                    } else {
                        return [];
                    }
                } else {
                    return $trims_costing->order->purchaseOrders->pluck('po_no')->unique()->flatMap(function ($po) use ($trims_costing, $level, $trimsActionStatus) {
                        if (isset($trims_costing->trimCosting->details['details'])) {
                            return $this->formatSearchValue($trims_costing->trimCosting->details['details'], $trims_costing, $po, $level, $trimsActionStatus);
                        } else {
                            return [];
                        }
                    });
                }
            });

            $costings = $trims_costings->filter(function ($item) use ($trimsIds) {
                if ($trimsIds) {
                    return in_array($item['item_id'], $trimsIds->all());
                }

                return true;
            })->values();

            return response()->json($costings, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     *
     */
    public function trimsBookingDetails(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $trims_booking = TrimsBooking::findOrFail($request->get('id'));
            $trims_booking_details = [];
            $bookingQuantities = [];

            foreach ($request->get('details') as $key => $details) {
                $findBooking = TrimsBookingDetails::where([
                    'item_id' => $details['item_id'],
                    'booking_id' => $request->get('id'),
                    'budget_unique_id' => $details['unique_id'],
                ])->first();
                if (!$findBooking) {
                    $formatter = new QtyWiseFormatter();

                    $data = [
                        'item_id' => $details['item_id'],
                        'nominated_supplier_id' => $details['nominated_supplier_id'],
                        'budget_unique_id' => $details['unique_id'],
                        'style_name' => $details['style_name'],
                        'po_no' => $details['po_no'],
                        'item_name' => $details['item_name'],
                        'item_description' => $details['item_description'],
                        'total_qty' => $details['total_quantity'],
                        'cons_uom_value' => $details['cons_uom_value'],
                        'cons_uom_id' => $details['cons_uom_id'],
                        'total_amount' => $details['total_amount'],
                        'breakdown' => $details['breakdown'] ?? '',
                    ];

                    $calculations = collect($formatter->format((object)$data, 'trims_booking'))->first();

                    $data = array_merge($data, [
                        'work_order_qty' => $details['balance_qty'],
                        'work_order_rate' => $calculations['rate'],
//                        'work_order_amount' => $calculations['amount'],
                        'work_order_amount' => format($details['balance_qty']) * format($calculations['rate']),
                    ]);

                    $bookingQuantities[] = [
                        'booking_id' => $request->get('id'),
                        'budget_unique_id' => $data['budget_unique_id'],
                        'item_id' => $data['item_id'],
                        'qty' => $details['balance_qty'],
                        'po_no' => $details['po_no'],
                        'created_at' => now(),
                        'updated_at' => now(),
                        'factory_id' => factoryId(),
                    ];

                    $trims_booking_details [] = $data;
                }
            }

            collect($trims_booking_details)->each(function ($value) {
                collect($value['breakdown'])->each(function ($breakdown) {
                    POBookingStatusService::statusUpdate($breakdown['po_no'], $breakdown['color_id'], $breakdown['size_id'], 'trims_booking');
                });
            });
            $trims_booking->bookingDetails()->createMany($trims_booking_details);
            TrimsBookingItemDetails::insert($bookingQuantities);

            DB::commit();
            $response = [
                'message' => 'Success',
                'trims_booking' => $trims_booking->load('bookingDetails'),
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getContrastTags($style): JsonResponse
    {
        try {

            $colors = Color::query()->where('style', $style)
                ->pluck('tag')
                ->unique()
                ->map(function ($value) use ($style) {
                    return [
                        'id' => $value,
                        'text' => $value,
                        'style' => $style,
                    ];
                })->values();

            return response()->json($colors, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getBookingDetails($id): JsonResponse
    {
        try {
            $trims_booking = TrimsBooking::with('bookingDetails')->findOrFail($id);
            $pi_details = ProformaInvoiceDetails::where([
                'booking_id' => $id,
                'type' => 'main-trims',
            ])->get()->pluck('booking_details_id');
            $response = [
                'message' => 'Success',
                'status' => Response::HTTP_OK,
                'trims_booking' => $trims_booking,
                'pi_details' => $pi_details,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function formatSearchValue($data, $trims_costing, $po, $level, $trimsActionStatus = null): Collection
    {
        $po_no = $level == 1 ? explode(',', $po) : (array)$po;

        $hasMatchingSupplier = collect($data)->where('nominated_supplier_id', request('supplier_id'))->count();

        return collect($data)
            ->filter(function ($trim) use ($hasMatchingSupplier) {
                if ($hasMatchingSupplier) {
                    return $trim['nominated_supplier_id'] == request('supplier_id');
                }

                return !$trim['nominated_supplier_id'];
            })
            ->filter(function ($trims) {
                if (request()->get('item')) {
                    return $trims['group_id'] == request()->get('item');
                }

                return true;
            })
            ->map(function ($val) use ($trims_costing, $po, $level, $po_no, $trimsActionStatus) {
                $total_qty = isset($val['breakdown']) ? collect($val['breakdown']['details'])
                    ->whereIn('po_no', $po_no)
                    ->sum('total_qty') : 0.00;

                $total_amount = (float)isset($val['breakdown']) ? collect($val['breakdown']['details'])
                    ->whereIn('po_no', $po_no)
                    ->sum('total_amount') : 0.00;

                $work_order_qty = TrimsBookingDetails::where([
                    'item_id' => $val['group_id'],
                    'budget_unique_id' => $trims_costing->job_no,
                    'po_no' => $po,
                ])->sum('work_order_qty');

                //$balance_qty = ((float)$total_qty) - ((float)$work_order_qty); it will throw unexpected output
                //$balance_qty = bcsub($total_qty, $work_order_qty, 4); php7.4
                $balance_qty = format($total_qty) - format($work_order_qty);

                $work_order_amount = TrimsBookingDetails::where([
                    'item_id' => $val['group_id'],
                    'budget_unique_id' => $trims_costing->job_no,
                    'po_no' => $po,
                ])->sum('work_order_amount');

                //$balance_amount = ((int)$total_amount) - ((int)$work_order_amount); it will throw unexpected output
                //$balance_amount = bcsub($total_amount, $work_order_amount, 4); php7.4
                $balance_amount = format($total_amount) - format($work_order_amount);

                return [
                    'unique_id' => $trims_costing->job_no,
                    'style_name' => $trims_costing->style_name,
                    'po_no' => $po,
                    'trimsActionStatus' => $trimsActionStatus,
                    'is_approve' => $trims_costing->is_approve,
                    'item_name' => $val['group_name'] ?? '',
                    'item_id' => $val['group_id'] ?? '',
                    'description' => $val['description'] ?? '',
                    'item_description' => $val['description'] ?? '',
                    'cons_uom_id' => $val['cons_uom_id'] ?? '',
                    'cons_uom_value' => $val['cons_uom_value'] ?? '',
                    'nominated_supplier_value' => $val['nominated_supplier_value'] ?? '',
                    'nominated_supplier_id' => $val['nominated_supplier_id'] ?? '',
                    'brand_id' => $val['brand_id'] ?? '',
                    'brand_value' => $val['brand_value'] ?? '',
                    'total_quantity' => (float)format($total_qty),
                    'total_amount' => (float)format($total_amount),
                    'balance_qty' => (float)format($balance_qty),
                    'balance_amount' => (float)format($balance_amount),
                    'breakdown' => isset($val['breakdown']) ?
                        collect($val['breakdown']['details'])->whereIn('po_no', $po_no)->map(function ($breakdown) use ($val) {
                            return array_merge($breakdown, [
                                'brand_id' => $val['brand_id'] ?? '',
                                'brand_value' => $val['brand_value'] ?? '',
                                'description' => $val['description'] ?? '',
                                'item_description' => $val['item_description'] ?? '',
                                'nominated_supplier_value' => $val['nominated_supplier_value'] ?? '',
                                'nominated_supplier_id' => $val['nominated_supplier_id'] ?? '',
                            ]);
                        })->values() : [],
                    'level' => $level,
                ];
            });
    }

    /**
     * @param $booking_id
     * @param $item_id
     * @return JsonResponse
     */
    public function deleteBookingDetails($booking_id, $item_id, $budgetId): JsonResponse
    {
        $booking_details_id = TrimsBookingDetails::where([
            'booking_id' => $booking_id,
            'item_id' => $item_id,
            'budget_unique_id' => $budgetId
        ])->first()->id;

        $proformaInvoiceStatus = ProformaInvoiceDetails::where([
            'booking_details_id' => $booking_details_id,
            'type' => 'main-trims',
        ])->first();
        if (!$proformaInvoiceStatus) {
            try {
                DB::beginTransaction();
                TrimsBookingItemDetails::query()
                    ->where([
                        'booking_id' => $booking_id,
                        'item_id' => $item_id,
                        'budget_unique_id' => $budgetId
                    ])->delete();
                TrimsBookingDetails::query()
                    ->where([
                        'booking_id' => $booking_id,
                        'item_id' => $item_id,
                        'budget_unique_id' => $budgetId
                    ])->delete();
                DB::commit();
                $response = [
                    'status' => Response::HTTP_OK,
                    'message' => 'Deleted Trims Booking Details',
                ];

                return response()->json($response, Response::HTTP_OK);
            } catch (Throwable $e) {
                return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            return response()->json(['message' => 'already used in proforma invoice'], Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function view($id)
    {
        $trimsBookings = TrimsBookingsReportService::bookingData($id);
        $extraFields = FieldsService::getAllFields();
        $extraFieldsKey = FieldsService::getKeys();
        $numberInWord = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        $total = (float)str_replace(',', '', number_format($trimsBookings['total'], 2));

        if (request('type') === 'v2') {
            $signature = ReportSignatureService::getApprovalSignature(TrimsBooking::class, $id);
        } elseif (request('type') === 'v3') {
            $signature = ReportSignatureService::getApprovalSignature(TrimsBooking::class, $id);
        } elseif (request('type') === 'v5') {
            $signature = ReportSignatureService::getApprovalSignature(TrimsBooking::class, $id);
        } elseif (request('type') === 'v6') {
            $signature = ReportSignatureService::getApprovalSignature(TrimsBooking::class, $id);
        } elseif (request('type') === 'v7') {
            $signature = ReportSignatureService::getApprovalSignature(TrimsBooking::class, $id);
        } elseif (request('type') === 'v8') {
            $signature = ReportSignatureService::getApprovalSignature(TrimsBooking::class, $id);
        } else {
            $signature = ReportSignatureService::getApprovalSignature(TrimsBooking::class, $id);
        }

        return view('merchandising::booking.reports.view', [
            'trimsBookings' => $trimsBookings['trimsBookings'],
            'trimsBookingsDetailsSizeSensitivity' => $trimsBookings['trimsBookingsDetailsSizeSensitivity'],
            'trimsBookingsDetailsColorAndSizeSensitivity' => $trimsBookings['trimsBookingsDetailsColorAndSizeSensitivity'],
            'trimsBookingsDetailsNoSensitivity' => $trimsBookings['trimsBookingsDetailsNoSensitivity'],
            'trimsBookingsDetailsContrastColorSensitivity' => $trimsBookings['trimsBookingsDetailsContrastColorSensitivity'],
            'trimsBookingsDetailsWithoutSensitivity' => $trimsBookings['trimsBookingsDetailsWithoutSensitivity'],
            'trimsBookingsDetailsAsPerGmtsColor' => $trimsBookings['trimsBookingsDetailsAsPerGmtsColor'],
            'payMode' => $trimsBookings['payMode'],
            'source' => $trimsBookings['source'],
            'totalAmount' => $trimsBookings['total'],
            'totalQty' => $trimsBookings['totalQty'],
            'amountInWord' => ucwords($numberInWord->format($total)),
            'type' => 'main',
            'signature' => $signature,
            'extraFields' => $extraFields,
            'extraFieldsKey' => $extraFieldsKey
        ]);
    }

    public function printView($id)
    {
        $trimsBookings = TrimsBookingsReportService::bookingData($id);
        $numberInWord = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        $total = (float)str_replace(',', '', number_format($trimsBookings['total'], 2));

        if (request('type') === 'v2') {
            $signature = ReportSignatureService::getApprovalSignature(TrimsBooking::class, $id);
        } elseif (request('type') === 'v3') {
            $signature = ReportSignatureService::getApprovalSignature(TrimsBooking::class, $id);
        } else {
            $signature = ReportSignatureService::getApprovalSignature(TrimsBooking::class, $id);
        }

        return view('merchandising::booking.reports.print', [
            'trimsBookings' => $trimsBookings['trimsBookings'],
            'trimsBookingsDetailsSizeSensitivity' => $trimsBookings['trimsBookingsDetailsSizeSensitivity'],
            'trimsBookingsDetailsColorAndSizeSensitivity' => $trimsBookings['trimsBookingsDetailsColorAndSizeSensitivity'],
            'trimsBookingsDetailsNoSensitivity' => $trimsBookings['trimsBookingsDetailsNoSensitivity'],
            'trimsBookingsDetailsContrastColorSensitivity' => $trimsBookings['trimsBookingsDetailsContrastColorSensitivity'],
            'trimsBookingsDetailsWithoutSensitivity' => $trimsBookings['trimsBookingsDetailsWithoutSensitivity'],
            'trimsBookingsDetailsAsPerGmtsColor' => $trimsBookings['trimsBookingsDetailsAsPerGmtsColor'],
            'payMode' => $trimsBookings['payMode'],
            'source' => $trimsBookings['source'],
            'totalAmount' => $trimsBookings['total'],
            'totalQty' => $trimsBookings['totalQty'],
            'amountInWord' => ucwords($numberInWord->format($total)),
            'type' => 'main',
            'signature' => $signature
        ]);
    }

    public function pdfView($id)
    {
        $trimsBookings = TrimsBookingsReportService::bookingData($id);
        $numberInWord = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        $total = (float)str_replace(',', '', number_format($trimsBookings['total'], 2));
        $extraFields = FieldsService::getAllFields();
        $extraFieldsKey = FieldsService::getKeys();
        if (request('type') === 'v2') {
            $signature = ReportSignatureService::getApprovalSignature(TrimsBooking::class, $id);
        } elseif (request('type') === 'v3') {
            $signature = ReportSignatureService::getApprovalSignature(TrimsBooking::class, $id);
        } elseif (request('type') === 'v5' || request('type') === 'v6') {
            $signature = ReportSignatureService::getApprovalSignature(TrimsBooking::class, $id);
        } else {
            $signature = ReportSignatureService::getApprovalSignature(TrimsBooking::class, $id);
        }

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('merchandising::booking.reports.pdf', [
                'trimsBookings' => $trimsBookings['trimsBookings'],
                'trimsBookingsDetailsSizeSensitivity' => $trimsBookings['trimsBookingsDetailsSizeSensitivity'],
                'trimsBookingsDetailsColorAndSizeSensitivity' => $trimsBookings['trimsBookingsDetailsColorAndSizeSensitivity'],
                'trimsBookingsDetailsNoSensitivity' => $trimsBookings['trimsBookingsDetailsNoSensitivity'],
                'trimsBookingsDetailsContrastColorSensitivity' => $trimsBookings['trimsBookingsDetailsContrastColorSensitivity'],
                'trimsBookingsDetailsWithoutSensitivity' => $trimsBookings['trimsBookingsDetailsWithoutSensitivity'],
                'trimsBookingsDetailsAsPerGmtsColor' => $trimsBookings['trimsBookingsDetailsAsPerGmtsColor'],
                'payMode' => $trimsBookings['payMode'],
                'source' => $trimsBookings['source'],
                'totalAmount' => $trimsBookings['total'],
                'totalQty' => $trimsBookings['totalQty'],
                'amountInWord' => ucwords($numberInWord->format($total)),
                'type' => 'main',
                'signature' => $signature,
                'extraFields' => $extraFields,
                'extraFieldsKey' => $extraFieldsKey
            ])->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer', compact('signature')),
            ]);

        return $pdf->stream("{$id}_trims_bookings.pdf");
    }

    public function excelView($id)
    {
        $trimsBookings = TrimsBookingsReportService::bookingData($id);
        $numberInWord = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        $total = (float)str_replace(',', '', number_format($trimsBookings['total'], 2));

        if (request('type') === 'v2') {
            $signature = ReportSignatureService::getApprovalSignature(TrimsBooking::class, $id);
        } elseif (request('type') === 'v3') {
            $signature = ReportSignatureService::getApprovalSignature(TrimsBooking::class, $id);
        } elseif (request('type') === 'v5') {
            $signature = ReportSignatureService::getApprovalSignature(TrimsBooking::class, $id);
        } elseif (request('type') === 'v6') {
            $signature = ReportSignatureService::getApprovalSignature(TrimsBooking::class, $id);

        } else {
            $signature = ReportSignatureService::getApprovalSignature(TrimsBooking::class, $id);
        }

        return Excel::download(new TrimsBookingExcel([
            'trimsBookings' => $trimsBookings['trimsBookings'],
            'trimsBookingsDetailsSizeSensitivity' => $trimsBookings['trimsBookingsDetailsSizeSensitivity'],
            'trimsBookingsDetailsColorAndSizeSensitivity' => $trimsBookings['trimsBookingsDetailsColorAndSizeSensitivity'],
            'trimsBookingsDetailsNoSensitivity' => $trimsBookings['trimsBookingsDetailsNoSensitivity'],
            'trimsBookingsDetailsContrastColorSensitivity' => $trimsBookings['trimsBookingsDetailsContrastColorSensitivity'],
            'trimsBookingsDetailsWithoutSensitivity' => $trimsBookings['trimsBookingsDetailsWithoutSensitivity'],
            'trimsBookingsDetailsAsPerGmtsColor' => $trimsBookings['trimsBookingsDetailsAsPerGmtsColor'],
            'payMode' => $trimsBookings['payMode'],
            'source' => $trimsBookings['source'],
            'totalAmount' => $trimsBookings['total'],
            'totalQty' => $trimsBookings['totalQty'],
            'amountInWord' => ucwords($numberInWord->format($total)),
            'type' => 'main',
            'signature' => $signature
        ]), 'trims-booking.xlsx');
    }

    public function bookingView($id)
    {
        $trimsBookings = TrimsBookingsReportService::bookingData($id);
        $extraFields = FieldsService::getAllFields();
        $extraFieldsKey = FieldsService::getKeys();
        $numberInWord = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        $total = (float)str_replace(',', '', number_format($trimsBookings['total'], 2));
        $signature = ReportSignatureService::getApprovalSignature(TrimsBooking::class, $id);
        //        dd($trimsBookings);
        return view('merchandising::booking.reports.view-9', [
            'trimsBookings' => $trimsBookings['trimsBookings'],
            'trimsBookingsDetailsSizeSensitivity' => $trimsBookings['trimsBookingsDetailsSizeSensitivity'],
            'trimsBookingsDetailsColorAndSizeSensitivity' => $trimsBookings['trimsBookingsDetailsColorAndSizeSensitivity'],
            'trimsBookingsDetailsNoSensitivity' => $trimsBookings['trimsBookingsDetailsNoSensitivity'],
            'trimsBookingsDetailsContrastColorSensitivity' => $trimsBookings['trimsBookingsDetailsContrastColorSensitivity'],
            'trimsBookingsDetailsWithoutSensitivity' => $trimsBookings['trimsBookingsDetailsWithoutSensitivity'],
            'trimsBookingsDetailsAsPerGmtsColor' => $trimsBookings['trimsBookingsDetailsAsPerGmtsColor'],
            'payMode' => $trimsBookings['payMode'],
            'source' => $trimsBookings['source'],
            'totalAmount' => $trimsBookings['total'],
            'totalQty' => $trimsBookings['totalQty'],
            'numberInWord' => $numberInWord,
            'amountInWord' => ucwords($numberInWord->format($total)),
            'type' => 'main',
            'signature' => $signature,
            'extraFields' => $extraFields,
            'extraFieldsKey' => $extraFieldsKey,
        ]);
    }

    public function bookingViewPDF($id)
    {
        $trimsBookings = TrimsBookingsReportService::bookingData($id);
        $extraFields = FieldsService::getAllFields();
        $extraFieldsKey = FieldsService::getKeys();
        $numberInWord = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        $total = (float)str_replace(',', '', number_format($trimsBookings['total'], 2));
        $signature = ReportSignatureService::getApprovalSignature(TrimsBooking::class, $id);

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('merchandising::booking.reports.view-9-pdf', [
                'trimsBookings' => $trimsBookings['trimsBookings'],
                'trimsBookingsDetailsSizeSensitivity' => $trimsBookings['trimsBookingsDetailsSizeSensitivity'],
                'trimsBookingsDetailsColorAndSizeSensitivity' => $trimsBookings['trimsBookingsDetailsColorAndSizeSensitivity'],
                'trimsBookingsDetailsNoSensitivity' => $trimsBookings['trimsBookingsDetailsNoSensitivity'],
                'trimsBookingsDetailsContrastColorSensitivity' => $trimsBookings['trimsBookingsDetailsContrastColorSensitivity'],
                'trimsBookingsDetailsWithoutSensitivity' => $trimsBookings['trimsBookingsDetailsWithoutSensitivity'],
                'trimsBookingsDetailsAsPerGmtsColor' => $trimsBookings['trimsBookingsDetailsAsPerGmtsColor'],
                'payMode' => $trimsBookings['payMode'],
                'source' => $trimsBookings['source'],
                'totalAmount' => $trimsBookings['total'],
                'totalQty' => $trimsBookings['totalQty'],
                'numberInWord' => $numberInWord,
                'amountInWord' => ucwords($numberInWord->format($total)),
                'type' => 'main',
                'signature' => $signature,
                'extraFields' => $extraFields,
                'extraFieldsKey' => $extraFieldsKey,
            ])->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer', compact('signature')),
            ]);

        return $pdf->stream("{$id}_trims_bookings.pdf");
    }

    public function bookingViewExcel($id)
    {
        $trimsBookings = TrimsBookingsReportService::bookingData($id);
        $extraFields = FieldsService::getAllFields();
        $extraFieldsKey = FieldsService::getKeys();
        $numberInWord = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        $total = (float)str_replace(',', '', number_format($trimsBookings['total'], 2));
        $signature = ReportSignatureService::getSignatures(TrimsBooking::class, $id);

        return Excel::download(new TrimsBookingExcel([
            'trimsBookings' => $trimsBookings['trimsBookings'],
            'trimsBookingsDetailsSizeSensitivity' => $trimsBookings['trimsBookingsDetailsSizeSensitivity'],
            'trimsBookingsDetailsColorAndSizeSensitivity' => $trimsBookings['trimsBookingsDetailsColorAndSizeSensitivity'],
            'trimsBookingsDetailsNoSensitivity' => $trimsBookings['trimsBookingsDetailsNoSensitivity'],
            'trimsBookingsDetailsContrastColorSensitivity' => $trimsBookings['trimsBookingsDetailsContrastColorSensitivity'],
            'trimsBookingsDetailsWithoutSensitivity' => $trimsBookings['trimsBookingsDetailsWithoutSensitivity'],
            'trimsBookingsDetailsAsPerGmtsColor' => $trimsBookings['trimsBookingsDetailsAsPerGmtsColor'],
            'payMode' => $trimsBookings['payMode'],
            'source' => $trimsBookings['source'],
            'totalAmount' => $trimsBookings['total'],
            'totalQty' => $trimsBookings['totalQty'],
            'numberInWord' => $numberInWord,
            'amountInWord' => ucwords($numberInWord->format($total)),
            'type' => 'main',
            'signature' => $signature,
            'extraFields' => $extraFields,
            'extraFieldsKey' => $extraFieldsKey,
        ]), "{$id}_trims_bookings.xlsx");
    }

    public function woWiseView($id)
    {
        $trimsBookings = TrimsBookingWoWiseReportService::mainBookingData($id);

        return view('merchandising::booking.reports.wo-wise-view', compact('trimsBookings'));
    }

    public function woWisePrintView($id)
    {
        $trimsBookings = TrimsBookingWoWiseReportService::mainBookingData($id);

        return view('merchandising::booking.reports.wo-wise-print', compact('trimsBookings'));
    }

    public function woWisePdf($id)
    {
        $trimsBookings = TrimsBookingWoWiseReportService::mainBookingData($id);
        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('merchandising::booking.reports.wo-wise-pdf', compact('trimsBookings'))->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->download("{$id}_trims_bookings.pdf");
    }

    public function fetchUniqueId(Request $req): JsonResponse
    {
        $uIdDetails = [];
        $trimsBookingDetails = TrimsBookingDetails::query()->whereIn('id', $req->ids)->get();
        foreach ($trimsBookingDetails as $trimsBookingDetail) {
            $uIdDetails[] = collect($trimsBookingDetail)->groupBy('budget_unique_id')->map(function ($uniqueIdWiseBreakdown) {
                return [
                    "budget_unique_id" => $uniqueIdWiseBreakdown[4],
                    "style_name" => $uniqueIdWiseBreakdown[5],
                    "item_name" => $uniqueIdWiseBreakdown[7],
                    "item_description" => $uniqueIdWiseBreakdown[8],
                    "total_qty" => $uniqueIdWiseBreakdown[9] . " " . $uniqueIdWiseBreakdown[10]
                ];
            });
        }

        $uIdDetails = collect($uIdDetails)->collapse()->unique('budget_unique_id')->values();

        return response()->json($uIdDetails);

    }

    public function fetchPo(Request $req)
    {
        $poDetails = [];
        $trimsBookingDetails = TrimsBookingDetails::query()->whereIn('id', $req->ids)->get();
        foreach ($trimsBookingDetails as $trimsBookingDetail) {
            //dump(collect($trimsBookingDetail->breakdown)->groupBy('po_no'));
            $poDetails[] = collect($trimsBookingDetail->breakdown)->groupBy('po_no')->map(function ($poWiseBreakdown) {

                return [
                    "po_no" => $poWiseBreakdown[0]['po_no'],
                    "booking_qty" => collect($poWiseBreakdown)->sum('qty'),
                    "item" => $poWiseBreakdown[0]['item'],
                    "color" => $poWiseBreakdown[0]['color'],
                    "country" => $poWiseBreakdown[0]['country'],
                    "supplier_name" => $poWiseBreakdown[0]['nominated_supplier_value'],
                ];
            });
        }

        $poDetails = collect($poDetails)->collapse()->unique('po_no');

        return response()->json($poDetails);

    }

    public function imageUpload(Request $request)
    {
        $image_path = null;
        if ($request->get('image') &&
            strpos($request->get('image'), 'image') !== false &&
            strpos($request->get('image'), 'base64') !== false) {
            $image_path = FileUploadRemoveService::fileUpload('trims', $request->get('image'), 'image');
        }
        return response()->json($image_path);
    }

    public function deleteUpload(Request $request)
    {
        try {
            FileUploadRemoveService::removeFile($request->get('image'));
            return response()->json($request->all());

        } catch (Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

    public function getFabricCompositions()
    {
        $compositions = NewFabricComposition::query()
            ->with('newFabricCompositionDetails.yarnComposition')
            ->get()
            ->map(function ($item) {
                $composition = collect($item->newFabricCompositionDetails)->map(function ($val) {
                    return $val->percentage . '% ' . $val->yarnComposition->yarn_composition ?? '';
                });

                return [
                    'id' => collect($composition)->implode(', '),
                    'text' => collect($composition)->implode(', '),
                ];
            });
        return response()->json($compositions);
    }

    public function TrimsListExcelAll(Request $request)
    {
        $q = request('search');
        $sort = request('sort') ?? 'desc';
        $pay_mode = array_search(($q), TrimsBooking::PAY_MODE) ?: $q;
        $source = array_search(($q), TrimsBooking::SOURCE) ?: $q;
//        dd($trimsBookings);
        return Excel::download(new TrimsExcel($q, $sort, $pay_mode, $source), 'trims-bookings-list-all.xlsx');
    }
}
