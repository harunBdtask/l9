<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use DB;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Constants\ApplicationConstant;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Builder;
use App\Exceptions\DeleteNotPossibleException;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricTransfer;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStockSummaryService;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricTransferDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetailsBreakdown;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStore\FabricTransfer\FabricTransferStrategy;

class FabricTransferController extends Controller
{
    public $response = [];
    public $status = 200;

    public function getReceiveDetails(Request $request)
    {
        return (new FabricTransferStrategy())->setStrategy($request->get('transfer_type'))->setRequest($request)
            ->getReceiveDetails();
    }

    public function transferDetail(Request $request)
    {
        $buyerId = $request->get('buyer_id');
        $poNo = $request->get('po_no');
        $uniqueId = $request->get('unique_id');
        $styleName = $request->get('style_name');

        return FabricBookingDetailsBreakdown::query()
            ->when($buyerId, function (Builder $builder) use ($buyerId) {
                $builder->whereHas('booking', function (Builder $builder) use ($buyerId) {
                    $builder->where('buyer_id', $buyerId);
                });
            })->when($poNo, function (Builder $builder) use ($poNo) {
                $builder->where('po_no', $poNo);
            })->when($uniqueId, function (Builder $builder) use ($uniqueId) {
                $builder->whereHas('booking', function (Builder $builder) use ($uniqueId) {
                    $builder->where('unique_id', $uniqueId);
                });
            })->when($styleName, function (Builder $builder) use ($styleName) {
                $builder->where('style_name', $styleName);
            })
            ->get()->groupBy('body_part_id')
            ->flatMap(function ($detailBreakDownBodyPart) {
                return $detailBreakDownBodyPart->groupBy('color_id')->map(function ($detailBreakDownColor) {
                    return $detailBreakDownColor->groupBy('style_name')->flatmap(function ($detailBreakDown) {
                        $styleName = $detailBreakDown->first()->budget->style_name;
                        $styleId = Order::query()->where('style_name', $styleName)->first()['id'] ?? null;
                        $detail = $detailBreakDown->first();

                        return [
                            'unique_id' => $detail->job_no,
                            'style_id' => $styleId,
                            'style_name' => $styleName,
                            'po_no' => $detail->po_no ?? null,
                        ];
                    });
                });
            });
    }

    public function index()
    {
        $transfers = FabricTransfer::query()->orderByDesc('id')->paginate();

        return view('inventory::fabrics.pages.fabric-transfers', ['transfers' => $transfers]);
    }

    public function create()
    {
        return view('inventory::fabrics.transfer');
    }

    public function store(Request $request, FabricTransfer $transfer): JsonResponse
    {
        try {
            $transfer->fill($request->all())->save();

            $this->response['message'] = ApplicationConstant::S_CREATED;
            $this->response['data'] = $transfer;
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
    public function storeDetail(Request $request, FabricTransfer $transfer): JsonResponse
    {
        try {
            DB::beginTransaction();
            $fromOrder = $request->get('from_order');
            $toOrder = $request->get('to_order');

            // From order stock summery update...
            $fromStockSummery = (new FabricStockSummaryService())->summary($fromOrder);
            $balanceQty = $fromStockSummery->balance - $fromOrder['transfer_qty'];
            $balanceAmount = $balanceQty * $fromOrder['rate'];
            $fromStockSummery->update([
                'balance' => $balanceQty,
                'balance_amount' => $balanceAmount,
                'transfer' => $fromOrder['transfer_qty'],
            ]);

            // To order stock summery update...
            $toStockSummery = (new FabricStockSummaryService())->summary($toOrder);
            $receiveAmount = $fromOrder['transfer_qty'] * $fromOrder['rate'];
            $balanceQty = $toStockSummery->balance + $fromOrder['transfer_qty'];
            $balanceAmount = $balanceQty * $fromOrder['rate'];
            $toStockSummery->update([
                'receive_qty' => $toStockSummery->receive_qty + $fromOrder['transfer_qty'],
                'receive_amount' => $toStockSummery->receive_amount + $receiveAmount,
                'balance' => $balanceQty,
                'balance_amount' => $balanceAmount,
            ]);

            // Detail data save...
            $transferDetail = $transfer->details()->findOrNew($request->get('id') ?? null);
            $transferDetail->fill($request->all())->save();
            DB::commit();

            $this->response['message'] = ApplicationConstant::S_CREATED;
            $this->response['data'] = $transferDetail;
            $this->status = Response::HTTP_CREATED;
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->response['message'] = $exception->getMessage();
            $this->status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($this->response, $this->status);
    }

    public function show(FabricTransfer $transfer): JsonResponse
    {
        $transfer->load('details');

        return response()->json($transfer, $this->status);
    }

    public function showDetail(FabricTransferDetail $transferDetail): array
    {
        return $this->formatDetail($transferDetail);
    }

    public function update(Request $request, FabricTransfer $transfer): JsonResponse
    {
        try {
            $transfer->fill($request->all())->save();
            $this->response['message'] = ApplicationConstant::S_CREATED;
            $this->response['data'] = $transfer;
            $this->status = Response::HTTP_CREATED;
        } catch (\Exception $exception) {
            $this->response['message'] = SOMETHING_WENT_WRONG;
            $this->response['errMsg'] = $exception->getMessage();
            $this->status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($this->response, $this->status);
    }

    /**
     * @throws Throwable
     */
    public function updateDetail(Request $request, FabricTransferDetail $transferDetail)
    {
        try {
            DB::beginTransaction();
            $fromOrder = $request->get('from_order');
            $toOrder = $request->get('to_order');
            $previousFromOrder = $transferDetail->from_order;
            $previousToOrder = $transferDetail->to_order;

            // Previous from order stock summery updated...
            $previousFromStockSummery = (new FabricStockSummaryService())->summary($previousFromOrder);
            $balanceQty = $previousFromStockSummery->balance + $previousFromOrder['transfer_qty'];
            $balanceAmount = $balanceQty * $previousFromOrder['rate'];
            $previousFromStockSummery->update([
                'balance' => $balanceQty,
                'balance_amount' => $balanceAmount,
                'transfer' => $previousFromStockSummery['transfer'] - $previousFromOrder['transfer_qty'],
            ]);

            // Previous to order stock summery updated...
            $previousToStockSummery = (new FabricStockSummaryService())->summary($previousToOrder);
            $receiveAmount = $previousFromOrder['transfer_qty'] * $previousFromOrder['rate'];
            $balanceQty = $previousToStockSummery->balance - $previousFromOrder['transfer_qty'];
            $balanceAmount = $balanceQty * $previousFromOrder['rate'];
            $previousToStockSummery->update([
                'receive_qty' => $previousToStockSummery->receive_qty - $previousFromOrder['transfer_qty'],
                'receive_amount' => $previousToStockSummery->receive_amount - $receiveAmount,
                'balance' => $balanceQty,
                'balance_amount' => $balanceAmount,
            ]);

            // From order stock summery updated...
            $fromStockSummery = (new FabricStockSummaryService())->summary($fromOrder);
            $balanceQty = $fromStockSummery->balance - $fromOrder['transfer_qty'];
            $balanceAmount = $balanceQty * $fromOrder['rate'];
            $fromStockSummery->update([
                'balance' => $balanceQty,
                'balance_amount' => $balanceAmount,
                'transfer' => $fromOrder['transfer_qty'],
            ]);

            // To order stock summery updated...
            $toStockSummery = (new FabricStockSummaryService())->summary($toOrder);
            $receiveAmount = $fromOrder['transfer_qty'] * $fromOrder['rate'];
            $balanceQty = $toStockSummery->balance + $fromOrder['transfer_qty'];
            $balanceAmount = $balanceQty * $fromOrder['rate'];
            $toStockSummery->update([
                'receive_qty' => $toStockSummery->receive_qty + $fromOrder['transfer_qty'],
                'receive_amount' => $toStockSummery->receive_amount + $receiveAmount,
                'balance' => $balanceQty,
                'balance_amount' => $balanceAmount,
            ]);

            // Transfer detail sata updated...
            $transferDetail->fill($request->all())->save();
            DB::commit();

            $this->response['message'] = ApplicationConstant::S_UPDATED;
            $this->response['data'] = $transferDetail;
            $this->status = Response::HTTP_CREATED;
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->response['message'] = $exception->getMessage();
            $this->status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }
    }

    public function destroy(FabricTransfer $transfer): RedirectResponse
    {
        try {
            if (count($transfer->details()->get())) {
                throw new DeleteNotPossibleException();
            }
            $transfer->delete();
            Session::flash('success', 'Data Deleted successfully!');
        } catch (\Exception $exception) {
            Session::flash('error', "Something went wrong! {$exception->getMessage()}");
        }

        return redirect()->back();
    }

    /**
     * @throws Throwable
     */
    public function destroyDetail(FabricTransferDetail $transferDetail): JsonResponse
    {
        try {
            DB::beginTransaction();
            $fromOrder = $transferDetail->from_order;
            $toOrder = $transferDetail->to_order;

            // From order stock summery update...
            $fromStockSummery = (new FabricStockSummaryService())->summary($fromOrder);
            $balanceQty = $fromStockSummery->balance + $fromOrder['transfer_qty'];
            $balanceAmount = $balanceQty * $fromOrder['rate'];
            $fromStockSummery->update([
                'balance' => $balanceQty,
                'balance_amount' => $balanceAmount,
                'transfer' => $fromStockSummery->transfer - $fromOrder['transfer_qty'],
            ]);

            // To order stock summery update...
            $toStockSummery = (new FabricStockSummaryService())->summary($toOrder);
            $receiveAmount = $fromOrder['transfer_qty'] * $fromOrder['rate'];
            $balanceQty = $toStockSummery->balance - $fromOrder['transfer_qty'];
            $balanceAmount = $balanceQty * $fromOrder['rate'];
            $toStockSummery->update([
                'receive_qty' => $toStockSummery->receive_qty - $fromOrder['transfer_qty'],
                'receive_amount' => $toStockSummery->receive_amount - $receiveAmount,
                'balance' => $balanceQty,
                'balance_amount' => $balanceAmount,
            ]);

            $transferDetail->delete();
            DB::commit();

            $this->response['message'] = ApplicationConstant::S_DELETED;
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->response['message'] = $exception->getMessage();
            $this->status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($this->response, $this->status);
    }

    public function formatDetail($detail): array
    {
        $summery = (new FabricStockSummaryService())->summary($detail->from_order);

        return [
            'id' => $detail->id,
            'unique_id' => $detail->unique_id,
            'transfer_id' => $detail->transfer_id,
            'from_order' => $detail->from_order,
            'to_order' => $detail->to_order,
            'display' => [
                'current_stock' => $summery->balance,
                'avg_rate' => $detail->from_order['rate'],
                'transfer_value' => null,
                'uom_id' => $detail->from_order['uom_id'],
                'uom_name' => $detail->from_order['uom_name'],
            ],
        ];
    }
}
