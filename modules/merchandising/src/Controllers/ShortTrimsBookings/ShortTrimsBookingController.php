<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\ShortTrimsBookings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use NumberFormatter;
use PDF;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoiceDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBookingDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortTrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortTrimsBookingDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortTrimsBookingItemDetails;
use SkylarkSoft\GoRMG\Merchandising\Services\Booking\QtyWiseFormatter;
use SkylarkSoft\GoRMG\Merchandising\Services\Booking\TrimsBookingsReportService;
use SkylarkSoft\GoRMG\Merchandising\Services\ChartService\Consumers\ShortTrimsBookingChartService;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\MerchandisingVariableSettings;
use SkylarkSoft\GoRMG\SystemSettings\Models\ShortBookingSettings;
use Symfony\Component\HttpFoundation\Response;

class ShortTrimsBookingController extends Controller
{
    public function index()
    {
        $paginateNumber = request('paginateNumber') ?? 15;
        $searchedValue = 15;

        $trimsBookings = ShortTrimsBooking::with([
            'buyer:id,name',
            'factory:id,group_name,factory_name,factory_short_name',
            'supplier:id,name'])
            ->latest()
            ->paginate($paginateNumber);
        $totalBookings = ShortTrimsBooking::all()->count();

        $chartService = new ShortTrimsBookingChartService();
        $dashboardOverview = $chartService->dashboardOverview();


        return view('merchandising::booking.short-trims-booking-index', compact('trimsBookings', 'dashboardOverview', 'paginateNumber', 'searchedValue', 'chartService'));
    }

    public function search()
    {
        $paginateNumber = request('paginateNumber') ?? 15;

        $q = request('search');
        $sort = request('sort') ?? 'desc';
        $pay_mode = array_search(($q), TrimsBooking::PAY_MODE) ?: $q;
        $source = array_search(($q), TrimsBooking::SOURCE) ?: $q;
        $booking_basis = array_search(($q), ShortTrimsBooking::BOOKING_BASIS) ?: $q;
        $material_source = array_search(($q), ShortTrimsBooking::MATERIAL_SOURCE) ?: $q;

        $trimsBookings = ShortTrimsBooking::query()
            ->with('buyer:id,name', 'factory:id,group_name,factory_name', 'supplier:id,name')
            ->where('location', 'like', '%' . $q . '%')
            ->orWhere('booking_date', 'like', '%' . $q . '%')
            ->orWhereHas('buyer', function ($query) use ($q) {
                return $query->where('name', 'LIKE', '%' . $q . '%');
            })
            ->orWhereHas('factory', function ($query) use ($q) {
                return $query->where('factory_name', 'LIKE', '%' . $q . '%');
            })
            ->orWhereHas('supplier', function ($query) use ($q) {
                return $query->where('name', 'LIKE', '%' . $q . '%');
            })
            ->orWhere('booking_basis', $booking_basis)
            ->orWhere('material_source', $material_source)
            ->orWhere('pay_mode', $pay_mode)
            ->orWhere('source', $source)
            ->orderBy('id', $sort)
            ->paginate($paginateNumber);
        $searchedValue = $trimsBookings->total();
        $totalBookings = ShortTrimsBooking::all()->count();

        $dashboardOverview = [
            "Total Bookings" => $totalBookings
        ];

        return view('merchandising::booking.short-trims-booking-index', compact('trimsBookings', 'dashboardOverview', 'paginateNumber', 'searchedValue'));
    }

    public function bookingMainPage()
    {
        return view('merchandising::booking.short-trims-booking');
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
            $booking = new ShortTrimsBooking($request->all([
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
//            $booking->step = 1;
            $booking->save();

            return response()->json(['message' => 'Successfully Saved!', 'booking' => $booking], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!', 'error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(ShortTrimsBooking $booking, Request $request)
    {
        $this->validateRequest($request);

        try {
            $booking->fill($request->all());
            $booking->save();

            return response()->json(['message' => 'Successfully Updated!', 'booking' => $booking], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!', 'error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function delete(ShortTrimsBooking $booking)
    {
        $proformaInvoiceStatus = ProformaInvoiceDetails::where([
            'booking_id' => $booking->id,
            'type' => 'short-trims',
        ])->get()->count();

        if (!$proformaInvoiceStatus) {
            try {
                $booking->delete();

                return response()->json(['message' => 'Successfully Deleted!'], Response::HTTP_OK);
            } catch (\Exception $e) {
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
            'ready_to_approve' => 'required',
        ], [
            'required' => 'Required',
        ]);
    }

    public function show($id)
    {
        $booking = ShortTrimsBooking::findOrFail($id);

        return response()->json($booking);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function trimsBookingSearch(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
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
                    $query->where('job_no', $unique_id);
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
            $percent = ShortBookingSettings::first()->trims_percentage ?? 0.00;
            $bookings = TrimsBookingDetails::when($unique_id, function ($query) use ($unique_id) {
                $query->where('budget_unique_id', $unique_id);
            })->get();
            $trims_costings = $trims_costings->flatMap(function ($trims_costing) use ($level, $bookings, $percent, $trimsActionStatus) {
                if ($level == 1) {
                    $po = $trims_costing->order->purchaseOrders->pluck('po_no')->unique()->implode(',');
                    if (isset($trims_costing->trimCosting->details['details'])) {
                        return $this->formatSearchValue($trims_costing->trimCosting->details['details'], $trims_costing, $po, $level, $bookings, $percent, $trimsActionStatus);
                    } else {
                        return [];
                    }
                } else {
                    return $trims_costing->order->purchaseOrders->pluck('po_no')->unique()->flatMap(function ($po) use ($trims_costing, $level, $bookings, $percent, $trimsActionStatus) {
                        if (isset($trims_costing->trimCosting->details['details'])) {
                            return $this->formatSearchValue($trims_costing->trimCosting->details['details'], $trims_costing, $po, $level, $bookings, $percent, $trimsActionStatus);
                        } else {
                            return [];
                        }
                    });
                }
            });

            return response()->json($trims_costings, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     *
     */
    public function trimsBookingDetails(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            DB::beginTransaction();
            $trims_booking = ShortTrimsBooking::findOrFail($request->get('id'));
            $trims_booking_details = [];
            $bookingQuantities = [];
            foreach ($request->get('details') as $key => $details) {
                $findBooking = ShortTrimsBookingDetails::where([
                    'item_id' => $details['item_id'],
                    'short_booking_id' => $request->get('id'),
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
                    $calculations = collect($formatter->format((object)$data, 'short_trims_booking'))->first();
                    $data = array_merge($data, [
                        'work_order_qty' => $calculations['wo_total_qty'],
                        'work_order_rate' => $calculations['rate'],
                        'work_order_amount' => $calculations['amount'],
                    ]);
                    $bookingQuantities[] = [
                        'short_booking_id' => $request->get('id'),
                        'budget_unique_id' => $data['budget_unique_id'],
                        'item_id' => $data['item_id'],
                        'qty' => $data['work_order_qty'],
                        'created_at' => now(),
                        'updated_at' => now(),
                        'factory_id' => factoryId(),
                    ];
                    $trims_booking_details [] = $data;
                }
            }
            $trims_booking->bookingDetails()->createMany($trims_booking_details);
            ShortTrimsBookingItemDetails::insert($bookingQuantities);
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

    public function getBookingDetails($id): \Illuminate\Http\JsonResponse
    {
        try {
            $trims_booking = ShortTrimsBooking::with('bookingDetails')->findOrFail($id);
            $pi_details = ProformaInvoiceDetails::where([
                'booking_id' => $id,
                'type' => 'short-trims',
            ])->get()->pluck('booking_details_id');
            $response = [
                'message' => 'Success',
                'status' => Response::HTTP_OK,
                'trims_booking' => $trims_booking,
                'pi_details' => $pi_details,

            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function formatSearchValue($data, $trims_costing, $po, $level, $bookings, $percent, $trimsActionStatus): \Illuminate\Support\Collection
    {
        $po_no = $level == 1 ? explode(',', $po) : (array)$po;
        $hasMatchingSupplier = collect($data)->where('nominated_supplier_id', request('supplier_id'))->count();

        return collect($data)
            ->filter(function ($trim) use ($hasMatchingSupplier) {
                if ($hasMatchingSupplier) {
                    return $trim['nominated_supplier_id'] == request('supplier_id');
                }

                return !$trim['nominated_supplier_id'];
            })->map(function ($val) use ($trims_costing, $po, $level, $po_no, $bookings, $percent, $trimsActionStatus) {

                $total_qty = isset($val['breakdown']) ? collect($val['breakdown']['details'])
                    ->whereIn('po_no', $po_no)
                    ->sum('total_qty') : 0.00;

                $total_amount = isset($val['breakdown']) ? collect($val['breakdown']['details'])
                    ->whereIn('po_no', $po_no)
                    ->sum('total_amount') : 0.00;

                $booking_work_order_sum = collect($bookings)
                    ->where('po_no', $po)
                    ->where('item_id', $val['group_id'])
                    ->sum('work_order_qty');

                $booking_percentage = $total_qty != 0 ? ($booking_work_order_sum / $total_qty) * 100 : 0;

                $work_order_qty = ShortTrimsBookingDetails::where([
                    'item_id' => $val['group_id'],
                    'budget_unique_id' => $trims_costing->job_no,
                    'po_no' => $po,
                ])->sum('work_order_qty');
                $balance_qty = $total_qty - $work_order_qty;

                $work_order_amount = ShortTrimsBookingDetails::where([
                    'item_id' => $val['group_id'],
                    'budget_unique_id' => $trims_costing->job_no,
                    'po_no' => $po,
                ])->sum('work_order_amount');
                $balance_amount = $total_amount - $work_order_amount;
                if ($booking_percentage > $percent) {
                    return [
                        'trimsActionStatus' => $trimsActionStatus,
                        'is_approved' => $trims_costing->is_approve ?? null,
                        'booking_percentage' => $booking_percentage,
                        'variable_percentage' => $percent,
                        'unique_id' => $trims_costing->job_no,
                        'style_name' => $trims_costing->style_name,
                        'po_no' => $po,
                        'item_name' => $val['group_name'] ?? '',
                        'item_id' => $val['group_id'] ?? '',
                        'description' => $val['description'] ?? '',
                        'item_description' => $val['item_description'] ?? '',
                        'cons_uom_id' => $val['cons_uom_id'] ?? '',
                        'cons_uom_value' => $val['cons_uom_value'] ?? '',
                        'nominated_supplier_value' => $val['nominated_supplier_value'] ?? '',
                        'nominated_supplier_id' => $val['nominated_supplier_id'] ?? '',
                        'brand_id' => $val['brand_id'] ?? '',
                        'brand_value' => $val['brand_value'] ?? '',
                        'total_quantity' => $total_qty,
                        'total_amount' => $total_amount,
                        'balance_qty' => $balance_qty,
                        'balance_amount' => $balance_amount,
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
                }
            })->filter(function ($value) {
                return $value !== null;
            });
    }

    /**
     * @param $booking_id
     * @param $item_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteBookingDetails($booking_id, $item_id): \Illuminate\Http\JsonResponse
    {
        $booking_details_id = ShortTrimsBookingDetails::where([
            'short_booking_id' => $booking_id,
            'item_id' => $item_id,
        ])->first()->id;
        $proformaInvoiceStatus = ProformaInvoiceDetails::where([
            'booking_details_id' => $booking_details_id,
            'type' => 'short-trims',
        ])->first();

        if (!$proformaInvoiceStatus) {
            try {
                DB::beginTransaction();
                ShortTrimsBookingItemDetails::where([
                    'short_booking_id' => $booking_id,
                    'item_id' => $item_id,
                ])->delete();
                ShortTrimsBookingDetails::where([
                    'short_booking_id' => $booking_id,
                    'item_id' => $item_id,
                ])->delete();
                DB::commit();
                $response = [
                    'status' => Response::HTTP_OK,
                    'message' => 'Deleted',
                ];

                return response()->json($response, Response::HTTP_OK);
            } catch (\Throwable $e) {
                return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            return response()->json(['message' => 'already used in proforma invoice'], Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function view($id)
    {
        $trimsBookings = TrimsBookingsReportService::shortBookingData($id);
        $numberInWord = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        $total = (float)str_replace(',', '', number_format($trimsBookings['total'], 2));
        $signature = ReportSignatureService::getApprovalSignature(ShortTrimsBooking::class, $id);
        return view('merchandising::booking.reports.short-trims-view', [
            'trimsBookings' => $trimsBookings['trimsBookings'],
            'trimsBookingsDetailsSizeSensitivity' => $trimsBookings['trimsBookingsDetailsSizeSensitivity'],
            'trimsBookingsDetailsColorAndSizeSensitivity' => $trimsBookings['trimsBookingsDetailsColorAndSizeSensitivity'],
            'trimsBookingsDetailsNoSensitivity' => $trimsBookings['trimsBookingsDetailsNoSensitivity'],
            'trimsBookingsDetailsContrastColorSensitivity' => $trimsBookings['trimsBookingsDetailsContrastColorSensitivity'],
            'totalAmount' => $trimsBookings['total'],
            'amountInWord' => ucwords($numberInWord->format($total)),
            'type' => 'short',
            'payMode' => TrimsBooking::PAY_MODE,
            'source' => TrimsBooking::SOURCE,
            'signature' => $signature
        ]);
    }

    public function printView($id)
    {
        $trimsBookings = TrimsBookingsReportService::shortBookingData($id);
        $numberInWord = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        $total = (float)str_replace(',', '', number_format($trimsBookings['total'], 2));
        $signature = ReportSignatureService::getApprovalSignature(ShortTrimsBooking::class, $id);
        return view('merchandising::booking.reports.print', [
            'trimsBookings' => $trimsBookings['trimsBookings'],
            'trimsBookingsDetailsSizeSensitivity' => $trimsBookings['trimsBookingsDetailsSizeSensitivity'],
            'trimsBookingsDetailsColorAndSizeSensitivity' => $trimsBookings['trimsBookingsDetailsColorAndSizeSensitivity'],
            'trimsBookingsDetailsNoSensitivity' => $trimsBookings['trimsBookingsDetailsNoSensitivity'],
            'trimsBookingsDetailsContrastColorSensitivity' => $trimsBookings['trimsBookingsDetailsContrastColorSensitivity'],
            'trimsBookingsDetailsWithoutSensitivity' => $trimsBookings['trimsBookingsDetailsWithoutSensitivity'],
            'trimsBookingsDetailsAsPerGmtsColor' => $trimsBookings['trimsBookingsDetailsAsPerGmtsColor'],
            'totalAmount' => $trimsBookings['total'],
            'amountInWord' => ucwords($numberInWord->format($total)),
            'type' => 'short',
            'payMode' => TrimsBooking::PAY_MODE,
            'source' => TrimsBooking::SOURCE,
            'signature' => $signature
        ]);
    }

    public function pdfView($id)
    {
        $trimsBookings = TrimsBookingsReportService::shortBookingData($id);
        $numberInWord = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        $total = (float)str_replace(',', '', number_format($trimsBookings['total'], 2));
        $signature = ReportSignatureService::getApprovalSignature(ShortTrimsBooking::class, $id);
        $pdf = PDF::setOption('enable-local-file-access', true)->loadView('merchandising::booking.reports.pdf', [
            'trimsBookings' => $trimsBookings['trimsBookings'],
            'trimsBookingsDetailsSizeSensitivity' => $trimsBookings['trimsBookingsDetailsSizeSensitivity'],
            'trimsBookingsDetailsColorAndSizeSensitivity' => $trimsBookings['trimsBookingsDetailsColorAndSizeSensitivity'],
            'trimsBookingsDetailsNoSensitivity' => $trimsBookings['trimsBookingsDetailsNoSensitivity'],
            'trimsBookingsDetailsContrastColorSensitivity' => $trimsBookings['trimsBookingsDetailsContrastColorSensitivity'],
            'trimsBookingsDetailsWithoutSensitivity' => $trimsBookings['trimsBookingsDetailsWithoutSensitivity'],
            'trimsBookingsDetailsAsPerGmtsColor' => $trimsBookings['trimsBookingsDetailsAsPerGmtsColor'],
            'totalAmount' => $trimsBookings['total'],
            'amountInWord' => ucwords($numberInWord->format($total)),
            'type' => 'short',
            'payMode' => TrimsBooking::PAY_MODE,
            'source' => TrimsBooking::SOURCE,
            'signature' => $signature
        ])->setPaper('a4')->setOptions([
            'footer-html' => view('skeleton::pdf.footer', compact('signature')),
        ]);

        return $pdf->stream("{$id}_short_trims_bookings.pdf");
    }
}
