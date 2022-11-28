<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\DyeingBatch;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Dyeing\Actions\SyncDyeingBatchAction;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch\DyeingBatch;
use SkylarkSoft\GoRMG\Dyeing\Requests\DyeingBatch\DyeingBatchDetailRequest;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch\DyeingBatchDetail;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\Formatters\DyeingBatchDetailFormatter;
use Throwable;

class DyeingBatchDetailController extends Controller
{

    /**
     * @param DyeingBatch $dyeingBatch
     * @param DyeingBatchDetailFormatter $formatter
     * @return JsonResponse
     */
    public function getDetails(DyeingBatch $dyeingBatch, DyeingBatchDetailFormatter $formatter): JsonResponse
    {
        try {
            $dyeingBatch->load('dyeingBatchDetails');

            $dyeingBatchDetails = $dyeingBatch->getRelation('dyeingBatchDetails')
                ->map(function ($collection) use ($formatter) {
                    return $formatter->format($collection);
                });

            return response()->json([
                'message' => 'Fetch dyeing batch details successfully',
                'data' => $dyeingBatchDetails,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'data' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Request $request
     * @param DyeingBatch $dyeingBatch
     * @param SyncDyeingBatchAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(Request               $request,
                          DyeingBatch           $dyeingBatch,
                          SyncDyeingBatchAction $action): JsonResponse
    {
        try {
            DB::beginTransaction();
            $dyeingBatch->dyeingBatchDetails()->createMany($request->dyeingBatchDetails);
            $action->syncBatchFromBatchDetails($dyeingBatch);
            $dyeingBatch->update(['sales_order_id' => $request->salesOrderId]);
            DB::commit();

            return response()->json([
                'message' => 'Dyeing batch details stored successfully',
                'data' => $dyeingBatch,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'data' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param DyeingBatchDetailRequest $request
     * @param DyeingBatch $dyeingBatch
     * @param SyncDyeingBatchAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(DyeingBatchDetailRequest $request,
                           DyeingBatch              $dyeingBatch,
                           SyncDyeingBatchAction    $action): JsonResponse
    {
        try {
            DB::beginTransaction();
            $dyeingBatch->dyeingBatchDetails()->updateOrCreate([
                'id' => $request->input('id'),
            ], $request->all());

            $action->syncBatchFromBatchDetails($dyeingBatch);
            DB::commit();

            return response()->json([
                'message' => 'Dyeing batch details updated successfully',
                'data' => $dyeingBatch,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param DyeingBatch $dyeingBatch
     * @param DyeingBatchDetail $dyeingBatchDetail
     * @param SyncDyeingBatchAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(DyeingBatch           $dyeingBatch,
                            DyeingBatchDetail     $dyeingBatchDetail,
                            SyncDyeingBatchAction $action): JsonResponse
    {
        try {
            DB::beginTransaction();
            $dyeingBatchDetail->delete();
            $action->syncBatchFromBatchDetails($dyeingBatch);
            DB::commit();

            return response()->json([
                'message' => 'Dyeing batch detail deleted successfully',
                'data' => $dyeingBatchDetail,
                'status' => Response::HTTP_NO_CONTENT,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'data' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
