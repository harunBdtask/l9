<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\SubTextileControllers\MaterialFabric;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Subcontract\Actions\TransferStockSummaryAction;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreFabricTransfer;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreFabricTransferDetail;
use SkylarkSoft\GoRMG\Subcontract\Requests\SubTextileRequests\FabricTransferDetailsFormRequest;
use SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\Formatters\SubGreyStoreTransferDetailFormatter;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class MaterialFabricTransferDetailsController extends Controller
{
    /**
     * @param SubGreyStoreFabricTransfer $transfer
     * @param SubGreyStoreTransferDetailFormatter $formatter
     * @return JsonResponse
     */
    public function getDetails(
        SubGreyStoreFabricTransfer          $transfer,
        SubGreyStoreTransferDetailFormatter $formatter
    ): JsonResponse {
        try {
            $loadDetails = [
                'details',
                'details.fromOrderDetail.color',
                'details.fromOrderDetail.colorType',
                'details.fromOrderDetail.subTextileOperation',
                'details.fromOrder',
                'details.toOrderDetail.color',
                'details.toOrderDetail.colorType',
                'details.toOrderDetail.subTextileOperation',
                'details.toOrder',
            ];

            $transfer->load($loadDetails);

            $details = $transfer->getRelation('details')->map(function ($detail) use ($formatter) {
                return $formatter->format($detail);
            });

            return response()->json([
                'message' => 'Fetch Fabric Transfer successfully',
                'data' => $details,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param FabricTransferDetailsFormRequest $request
     * @param SubGreyStoreFabricTransfer $transfer
     * @param TransferStockSummaryAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(
        FabricTransferDetailsFormRequest $request,
        SubGreyStoreFabricTransfer       $transfer,
        TransferStockSummaryAction       $action
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $detail = $transfer->details()->create($request->all());
            $detail->detailMSI()->create($request->merge([
                'fabric_transfer_id' => $transfer->id,
            ])->all());
            $action->attachToStockSummaryReport($detail);
            $action->attachToDailyStockSummaryReport($detail);
            DB::commit();

            return response()->json([
                'message' => 'Fabric Transfer created successfully',
                'data' => $transfer,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json([
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param SubGreyStoreFabricTransferDetail $detail
     * @param SubGreyStoreTransferDetailFormatter $formatter
     * @return JsonResponse
     */
    public function edit(
        SubGreyStoreFabricTransferDetail    $detail,
        SubGreyStoreTransferDetailFormatter $formatter
    ): JsonResponse {
        try {
            $loadDetails = [
                'fromOrderDetail.color',
                'fromOrderDetail.colorType',
                'fromOrderDetail.subTextileOperation',
                'fromOrderDetail.subGreyStoreReceiveDetail',
                'fromOrder',
                'toOrderDetail.color',
                'toOrderDetail.colorType',
                'toOrderDetail.subTextileOperation',
                'toOrderDetail.subGreyStoreIssueDetail',
                'toOrder',
            ];

            $detail->load($loadDetails);

            return response()->json([
                'message' => 'Fabric Transfer created successfully',
                'data' => $formatter->format($detail),
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param FabricTransferDetailsFormRequest $request
     * @param SubGreyStoreFabricTransferDetail $detail
     * @param TransferStockSummaryAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(
        FabricTransferDetailsFormRequest $request,
        SubGreyStoreFabricTransferDetail $detail,
        TransferStockSummaryAction       $action
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $detail->fill($request->all())->save();
            $detail->detailMSI()->updateOrCreate([
                'transfer_detail_id' => $detail->id,
            ], $request->all());
            $action->attachToStockSummaryReport($detail);
            $action->attachToDailyStockSummaryReport($detail);
            DB::commit();

            return response()->json([
                'message' => 'Fabric Transfer Update successfully',
                'data' => $detail,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param SubGreyStoreFabricTransferDetail $detail
     * @param TransferStockSummaryAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(
        SubGreyStoreFabricTransferDetail $detail,
        TransferStockSummaryAction       $action
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $detail->load('detailMSI');
            $detail->detailMSI()->delete();
            $detail->delete();
            $action->attachToStockSummaryReport($detail);
            $action->attachToDailyStockSummaryReport($detail);
            DB::commit();

            return response()->json([
                'message' => 'Fabric Transfer Delete successfully',
                'status' => Response::HTTP_NO_CONTENT,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
