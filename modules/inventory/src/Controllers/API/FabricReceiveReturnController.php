<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use Throwable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Constants\ApplicationConstant;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceive;
use SkylarkSoft\GoRMG\Inventory\Requests\FabricReceiveReturnRequest;
use SkylarkSoft\GoRMG\Inventory\Exceptions\InvalidBookingNoException;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceiveReturn;
use SkylarkSoft\GoRMG\Inventory\Requests\FabricReceiveReturnDetailsRequest;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceiveReturnDetail;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStore\FabricReceiveReturn\FabricReceiveReturnStrategy;

class FabricReceiveReturnController extends Controller
{
    const MRR_NO = 'mrr_no';
    const CHALLAN_NO = 'challan_no';
    const BATCH_NO = 'batch_no';
    const BOOKING_NO = 'booking_no';

    public $status = Response::HTTP_OK;

    public function index(FabricReceiveReturn $receive,Request $request)
    {
        $search = $request->search;
        $return = $receive->load('details')->search($search)->latest()->paginate(15);
        return view('inventory::fabrics.pages.fabric_receive_return_index', compact('return'));
    }

    public function create()
    {
        return view('inventory::fabrics.receive-returns');
    }

    public function searchFabricReceiveReturn(Request $request)
    {
        $search_by = $request->get('search_by');
        $mrr_no = $request->get('mrr_no');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        return FabricReceive::query()->with('details')
            ->withSum([
                'details' => function (Builder $query) use ($search_by, $mrr_no) {
                    $query->when($search_by === self::BATCH_NO, function ($query) use ($mrr_no) {
                        $query->where('batch_no', $mrr_no);
                    });
                },
            ], 'receive_qty')
            ->when($start_date && $end_date, function (Builder $query) use ($start_date, $end_date) {
                $query->whereBetween('receive_date', [$start_date, $end_date]);
            })
            ->where('status', FabricReceive::APPROVE)
            ->when($mrr_no, function ($query) use ($search_by, $mrr_no) {
                $query->when($search_by === self::MRR_NO, function ($query) use ($mrr_no) {
                    $query->where('receive_no', $mrr_no);
                })->when($search_by === self::CHALLAN_NO, function ($query) use ($mrr_no) {
                    $query->where('receive_challan', $mrr_no);
                })->when($search_by === self::BATCH_NO, function ($query) use ($mrr_no) {
                    $query->whereHas('details', function ($query) use ($mrr_no) {
                        $query->where('batch_no', $mrr_no);
                    });
                })->when($search_by === self::BOOKING_NO, function ($query) use ($mrr_no) {
                    $query->whereHas('booking', function ($query) use ($mrr_no) {
                        $query->where('unique_id', $mrr_no);
                    });
                });
            })
            ->get()->map(function ($receive) {

                return [
                    'id' => $receive->id,
                    'mrr_no' => $receive->receive_no,
                    'year' => Carbon::createFromFormat('Y-m-d', $receive->receive_date)->format('Y'),
                    'batch_no' => '',
                    'receive_basis' => $receive->receive_basis,
                    'booking_no' => $receive->booking->unique_id,
                    'receivable_id' => $receive->receivable_id,
                    'receive_challan' => $receive->receive_challan,
                    'store_id' => $receive->store_id,
                    'store' => $receive->store->name,
                    'receive_date' => $receive->receive_date,
                    'deying_source' => $receive->dyeing_source,
                    'receive_qty' => number_format($receive->details_sum_receive_qty, 2),
                ];
            });
    }

    public function fabricReceiveReturnDetails(Request $request)
    {
        return (new FabricReceiveReturnStrategy())->setStrategy($request->get('receive_return_type'))
            ->setRequest($request)
            ->getReceiveDetails();
    }

    public function store(FabricReceiveReturnRequest $request, FabricReceiveReturn $return): JsonResponse
    {
        try {
            $return->fill($request->all())->save();

            $this->response['message'] = ApplicationConstant::S_CREATED;
            $this->response['data'] = $return;
            $this->status = Response::HTTP_CREATED;
        } catch (\Exception $exception) {
            $this->response['message'] = $exception->getMessage();
            $this->status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($this->response, $this->status);
    }

    /**
     * @throws Throwable
     */
    public function storeDetails(FabricReceiveReturnDetailsRequest $request, FabricReceiveReturn $receive): JsonResponse
    {
        try {
            DB::beginTransaction();
            if (!$receive->mrr_no) {
                $receive->update(['mrr_no' => $request->mrr_no]);
            }

            if ($receive->mrr_no != $request->mrr_no) {
                throw new InvalidBookingNoException('MRR No Not Matched');
            }

            $receiveDetail = (new FabricReceiveReturnStrategy())->setStrategy($request->get('receive_return_type'))
                ->setRequest($request)
                ->setReceiveReturnModel($receive)
                ->storeDetail();
            DB::commit();

            $this->response['message'] = ApplicationConstant::S_CREATED;
            $this->response['data'] = $receiveDetail->load('receiveDetail', 'color', 'uom');
            $this->status = Response::HTTP_CREATED;
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->response['message'] = $exception->getMessage();
            $this->status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($this->response, $this->status);
    }

    /**
     * @throws Throwable
     */
    public function storeDetailsRemarks(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            foreach ($request->all() as $detail) {
                $receiveReturnDetail = FabricReceiveReturnDetail::query()->findOrFail($detail['id']);
                $receiveReturnDetail->update([
                    'remarks' => $detail['remarks'],
                ]);
            }
            DB::commit();

            return response()->json(['message' => 'Remarks saved successfully'], Response::HTTP_CREATED);
        } catch (Throwable $exception) {
            DB::rollBack();

            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(FabricReceiveReturn $receiveReturn): JsonResponse
    {
        $receiveReturn->load('details', 'details.color', 'details.uom', 'details.receiveDetail');

        return response()->json($receiveReturn, Response::HTTP_OK);
    }

    private function formatReceiveReturnDetails($detail, $global_stock): array
    {
        return [
            'unique_id' => $detail->unique_id,
            'fabric_receive_detail_id' => $detail->id,
            'product_name' => $detail->item->item_name,
            'buyer_id' => $detail->buyer_id,
            'style_id' => $detail->style_id,
            'style_name' => $detail->style_name,
            'batch_no' => $detail->batch_no,
            'gmts_item_id' => $detail->gmts_item_id,
            'body_part_id' => $detail->body_part_id,
            'body_part' => $detail->body->name,
            'fabric_composition_id' => $detail->fabric_composition_id,
            'construction' => $detail->construction,
            'fabric_description' => $detail->fabric_description,
            'dia' => $detail->dia,
            'gsm' => $detail->gsm,
            'dia_type' => $detail->dia_type,
            'color' => $detail->fabricColor->name,
            'color_id' => $detail->color_id,
            'contrast_color_id' => $detail->contrast_color_id,
            'uom' => $detail->uom->unit_of_measurement,
            'uom_id' => $detail->uom_id,
            'return_qty' => null,
            'rate' => $detail->rate,
            'amount' => null,
            'fabric_shade' => $detail->fabric_shade,
            'no_of_roll' => $detail->no_of_roll,
            'store_id' => $detail->receive->store_id,
            'store_name' => $detail->receive->store->name,
            'floor' => $detail->floor->name,
            'floor_id' => $detail->floor_id,
            'room' => $detail->room->name,
            'room_id' => $detail->room_id,
            'rack' => $detail->rack->name,
            'rack_id' => $detail->rack_id,
            'shelf' => $detail->shelf->name,
            'shelf_id' => $detail->shelf_id,
            'remarks' => null,
            'color_type_id' => $detail->color_type_id,
            'booking_no' => $detail->receive->booking->unique_id,
            'current_stock' => $global_stock,
            'fabric_receive' => $detail->receive_qty,
            'cumulative_return' => $detail->return_details_sum_return_qty ?? 0,
            'yet_to_issue' => $detail->issue_details_sum_issue_qty,
            'global_stock' => $global_stock,
            'mrr_no' => $detail->receive->receive_no,
        ];
    }
}
