<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\SubTextileControllers\MaterialFabric;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Subcontract\Actions\StockSummaryAction;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreReceive;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreReceiveDetails;
use SkylarkSoft\GoRMG\Subcontract\Requests\SubTextileRequests\FabricReceiveDetailsFormRequest;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class MaterialFabricReceiveDetailsController extends Controller
{
    /**
     * @param SubGreyStoreReceive $subGreyStoreReceive
     * @return JsonResponse
     */
    public function getDetails(SubGreyStoreReceive $subGreyStoreReceive): JsonResponse
    {
        try {
            $loadRelations = [
                'receiveDetails.operation',
                'receiveDetails.fabricComposition',
                'receiveDetails.fabricType',
                'receiveDetails.color',
                'receiveDetails.colorType',
                'receiveDetails.unitOfMeasurement',
                'receiveDetails.bodyPart',
            ];

            return response()->json([
                'message' => 'fabric-receive fetched successfully',
                'data' => $subGreyStoreReceive->load($loadRelations),
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getChallanDetails(SubGreyStoreReceive $subGreyStoreReceive): JsonResponse
    {
        try {
            $loadRelations = [
                'receiveDetailsByChallanNo.textileOrder',
                'receiveDetailsByChallanNo.operation',
                'receiveDetailsByChallanNo.fabricComposition',
                'receiveDetailsByChallanNo.fabricType',
                'receiveDetailsByChallanNo.color',
                'receiveDetailsByChallanNo.colorType',
                'receiveDetailsByChallanNo.unitOfMeasurement',
                'receiveDetailsByChallanNo.bodyPart',
            ];

            return response()->json([
                'message' => 'fabric-receive fetched successfully',
                'data' => $subGreyStoreReceive->load($loadRelations),
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param FabricReceiveDetailsFormRequest $request
     * @param SubGreyStoreReceiveDetails $subGreyStoreReceiveDetails
     * @param StockSummaryAction $stockSummaryAction
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(
        FabricReceiveDetailsFormRequest $request,
        SubGreyStoreReceiveDetails      $subGreyStoreReceiveDetails,
        StockSummaryAction              $stockSummaryAction
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $subGreyStoreReceiveDetails->fill($request->all())->save();
            $stockSummaryAction->attachToStockSummaryReport($subGreyStoreReceiveDetails);
            $stockSummaryAction->attachToDailyStockSummaryReport($subGreyStoreReceiveDetails);
            DB::commit();

            return response()->json([
                'message' => 'fabric-receive details created successfully',
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param FabricReceiveDetailsFormRequest $request
     * @param SubGreyStoreReceiveDetails $subGreyStoreReceiveDetails
     * @param StockSummaryAction $stockSummaryAction
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(
        FabricReceiveDetailsFormRequest $request,
        SubGreyStoreReceiveDetails      $subGreyStoreReceiveDetails,
        StockSummaryAction              $stockSummaryAction
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $subGreyStoreReceiveDetails->fill($request->all())->save();
            $stockSummaryAction->attachToStockSummaryReport($subGreyStoreReceiveDetails);
            $stockSummaryAction->attachToDailyStockSummaryReport($subGreyStoreReceiveDetails);
            DB::commit();

            return response()->json([
                'message' => 'fabric-receive details updated successfully',
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param SubGreyStoreReceiveDetails $subGreyStoreReceiveDetails
     * @param StockSummaryAction $stockSummaryAction
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(
        SubGreyStoreReceiveDetails $subGreyStoreReceiveDetails,
        StockSummaryAction         $stockSummaryAction
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $stockSummaryAction->attachToStockSummaryReport($subGreyStoreReceiveDetails);
            $stockSummaryAction->attachToDailyStockSummaryReport($subGreyStoreReceiveDetails);
            $subGreyStoreReceiveDetails->delete();
            DB::commit();

            return response()->json([
                'message' => 'fabric-receive-details deleted successfully',
                'data' => [],
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
