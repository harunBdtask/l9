<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Filters\Filter;
use SkylarkSoft\GoRMG\Merchandising\Models\Samples\SampleRequisition;
use SkylarkSoft\GoRMG\Merchandising\Models\SampleTrimsBookingDetail;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Merchandising\Models\SampleTrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Requests\SampleTrimsBookingFormRequest;
use PDF;

class SampleTrimsBookingController extends Controller
{
    public function index()
    {   
        $paginateNumber = request('paginateNumber') ?? 15;
        $searchedOrders = 15;
        $bookings = SampleTrimsBooking::query()
            ->with(['factory:id,factory_name', 'buyer:id,name', 'supplier:id,name'])
            ->orderBy('id', 'desc')
            ->paginate($paginateNumber);
        $totalTrims  = SampleTrimsBooking::all()->count();        
        $dashboardOverview = [
            "Total Bookings" => $totalTrims 
        ];
        return view('merchandising::booking.sample-trims-booking.index', compact('bookings','dashboardOverview','paginateNumber','searchedOrders'));
    }

    public function sampleTrimsPages()
    {
        return view('merchandising::booking.sample-trims-booking.sample-trims-booking');
    }

    public function store(SampleTrimsBookingFormRequest $request): JsonResponse
    {
        try {
            if ($request->get('id')) {
                $sampleTrimsBooking = SampleTrimsBooking::query()->findOrFail($request->get('id'));
                $status = 200;
            } else {
                $sampleTrimsBooking = new SampleTrimsBooking();
                $status = 201;
            }
            $sampleTrimsBooking->fill($request->all())->save();
            return response()->json($sampleTrimsBooking, $status);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function storeDetails(Request $request): JsonResponse
    {
        try {
            foreach ($request->all() as $detail) {
                SampleTrimsBookingDetail::query()
                    ->updateOrCreate([
                        'style_name' => $detail['style_name'],
                        'item_id' => $detail['item_id'],
                        'uom_id' => $detail['uom_id'],
                    ], $detail);
            }
            return response()->json('Created', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(SampleTrimsBooking $sampleTrimsBooking): JsonResponse
    {
        try {
            $data = $sampleTrimsBooking;
            $details = SampleTrimsBookingDetail::query()
                ->where('sample_trims_booking_id', $sampleTrimsBooking->id)
                ->get();
            return response()->json(['data' => $data, 'details' => $details], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function filterData(Request $request): JsonResponse
    {
        $data = SampleRequisition::query()
            ->where('factory_id', $request->get('factory_id'))
            ->where('buyer_id', $request->get('buyer_id'))
            ->get();

        $styles = collect($data)->pluck('style_name')->unique()->values();
        $req_nos = collect($data)->pluck('requisition_id')->unique()->values();

        return response()->json([
            'style_names' => $styles,
            'requisition_nos' => $req_nos,
        ]);
    }

    public function filterRequisitionData(Request $request): JsonResponse
    {
        $requisitions = SampleRequisition::query()
            ->where('factory_id', $request->get('factory_id'))
            ->where('buyer_id', $request->get('buyer_id'))
            ->when($request->get('requisition_no'), Filter::applyFilter('requisition_id', $request->get('requisition_no')))
            ->when($request->get('style_name'), Filter::applyFilter('style_name', $request->get('style_name')))
            ->when($request->get('from_date') && $request->get('to_date'), function ($q) use ($request) {
                $q->whereBetween('req_date', [$request->get('from_date'), $request->get('to_date')]);
            })
            ->with(['accessories.item:id,item_group', 'accessories.uom:id,unit_of_measurement'])
            ->get()
            ->flatMap(function ($requisition) {
                return $requisition->accessories->map(function ($item) use ($requisition) {
                    $cu_qty = SampleTrimsBookingDetail::query()
                        ->where('style_name', $requisition->style_name)
                        ->where('item_id', $item->item_id)
                        ->where('uom_id', $item->uom_id)
                        ->sum('wo_qty');
                    $balance_qty = $item->req_qty - $cu_qty;
                    return [
                        'requisition_no' => $requisition->requisition_id,
                        'style_name' => $requisition->style_name,
                        'item_id' => $item->item_id,
                        'uom_id' => $item->uom_id,
                        'item_names' => $item->item->item_group ?? '',
                        'item_des' => $item->description,
                        'uom_values' => $item->uom->unit_of_measurement ?? '',
                        'total_qty' => $item->total_qty,
                        'req_qty' => $item->req_qty,
                        'cu_wo' => $cu_qty,
                        'balance_wo_qty' => $balance_qty,
                        'balance_amount' => $balance_qty * $item->rate,
                        'wo_qty' => $cu_qty,
                        'rate' => $item->rate,
                        'amount' => $cu_qty * $item->rate,
                        'dealing_merchant_id' => $requisition->dealing_merchant_id,
                    ];
                });
            });

        return response()->json($requisitions, Response::HTTP_OK);
    }

    public function deleteSample(SampleTrimsBooking $sampleTrimsBooking): JsonResponse
    {
        try {
            DB::beginTransaction();
            $sampleTrimsBooking->details()->delete();
            $sampleTrimsBooking->delete();
            DB::commit();
            return response()->json('Delete Success', Response::HTTP_OK);
        } catch (\Throwable $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteDetail(SampleTrimsBookingDetail $sampleTrimsBookingDetail): JsonResponse
    {
        try {
            $sampleTrimsBookingDetail->delete();
            return response()->json('Delete Success', Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function view($id)
    {
        $data = $this->sampleBookingData($id);
        return view('merchandising::booking.sample-trims-booking.view', compact('data'));
    }

    public function pdf($id)
    {
        $data = $this->sampleBookingData($id);
        $pdf = PDF::setOption('enable-local-file-access', true)->loadView('merchandising::booking.sample-trims-booking.pdf',
            compact('data')
        )->setPaper('a4')->setOrientation('landscape')->setOptions([
            'header-html' => view('skeleton::pdf.header'),
            'footer-html' => view('skeleton::pdf.footer'),
        ]);
        return $pdf->stream('sample_trims_booking_report');
    }

    private function sampleBookingData($id)
    {
        return SampleTrimsBooking::query()
            ->where('id', $id)
            ->with(['supplier:id,name', 'buyer:id,name', 'factory:id,factory_name', 'details.merchant:id,screen_name'])
            ->firstOrFail();
    }
}
