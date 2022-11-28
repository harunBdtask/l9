<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Controllers\V3\TrimsStoreReceiveReturn;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\TrimsStore\Actions\V3\StockSummaryAction;
use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreReceiveReturn\TrimsStoreReceiveReturn;
use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreReceiveReturn\TrimsStoreReceiveReturnDetail;
use SkylarkSoft\GoRMG\TrimsStore\Requests\V3\TrimsStoreReceiveReturn\TrimsStoreReceiveReturnDetailFormRequest;
use SkylarkSoft\GoRMG\TrimsStore\Services\V3\Formatter\TrimsReceiveReturnDetailsFormatter;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TrimsStoreReceiveReturnDetailsController extends Controller
{
    /**
     * @param TrimsStoreReceiveReturn $receiveReturn
     * @param TrimsReceiveReturnDetailsFormatter $formatter
     * @return JsonResponse
     */
    public function getDetails(
        TrimsStoreReceiveReturn            $receiveReturn,
        TrimsReceiveReturnDetailsFormatter $formatter
    ): JsonResponse {
        try {
            $receiveReturn->load(
                'details.currency',
                'details.uom',
                'details.floor',
                'details.buyer',
                'details.supplier',
                'details.color',
                'details.itemGroup',
                'details.room',
                'details.rack',
                'details.shelf',
                'details.bin',
                'details.order'
            );

            $details = $receiveReturn->getRelation('details')
                ->map(function ($detail) use ($formatter) {
                    return $formatter->format($detail);
                });

            return response()->json([
                'message' => 'Fetch trims store receive return details successfully',
                'data' => $details,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
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
     * @param TrimsStoreReceiveReturnDetailFormRequest $request
     * @param TrimsStoreReceiveReturn $receiveReturn
     * @param StockSummaryAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(
        TrimsStoreReceiveReturnDetailFormRequest $request,
        TrimsStoreReceiveReturn                  $receiveReturn,
        StockSummaryAction                       $action
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $detail = $receiveReturn->details()->create($request->all());
            $action->attachToStockSummary($detail);
            $action->attachToDailyStockSummary($detail);
            DB::commit();

            return response()->json([
                'message' => 'Trims store receive return detail stored successfully',
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
     * @param TrimsStoreReceiveReturnDetailFormRequest $request
     * @param TrimsStoreReceiveReturnDetail $detail
     * @param StockSummaryAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(
        TrimsStoreReceiveReturnDetailFormRequest $request,
        TrimsStoreReceiveReturnDetail            $detail,
        StockSummaryAction                       $action
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $detail->fill($request->all())->save();
            $action->attachToStockSummary($detail);
            $action->attachToDailyStockSummary($detail);
            DB::commit();

            return response()->json([
                'message' => 'Trims store receive return detail stored successfully',
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
     * @param TrimsStoreReceiveReturnDetail $detail
     * @param StockSummaryAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(
        TrimsStoreReceiveReturnDetail $detail,
        StockSummaryAction            $action
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $detail->delete();
            $action->attachToStockSummary($detail);
            $action->attachToDailyStockSummary($detail);
            DB::commit();

            return response()->json([
                'message' => 'Trims store receive return detail deleted successfully',
                'data' => $detail,
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
