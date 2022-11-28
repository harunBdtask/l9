<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Controllers\V3\TrimsStoreIssue;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\TrimsStore\Actions\V3\StockSummaryAction;
use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreIssue\TrimsStoreIssue;
use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreIssue\TrimsStoreIssueDetail;
use SkylarkSoft\GoRMG\TrimsStore\Requests\V3\TrimsStoreIssue\TrimsStoreIssueDetailFormRequest;
use SkylarkSoft\GoRMG\TrimsStore\Services\V3\Formatter\TrimsIssueDetailsFormatter;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TrimsStoreIssueDetailsController extends Controller
{
    /**
     * @param TrimsStoreIssue $issue
     * @param TrimsIssueDetailsFormatter $formatter
     * @return JsonResponse
     */
    public function getDetails(
        TrimsStoreIssue            $issue,
        TrimsIssueDetailsFormatter $formatter
    ): JsonResponse {
        try {
            $issue->load(
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

            $details = $issue->getRelation('details')
                ->map(function ($detail) use ($formatter) {
                    return $formatter->format($detail);
                });

            return response()->json([
                'message' => 'Fetch trims store issue details successfully',
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
     * @param TrimsStoreIssueDetailFormRequest $request
     * @param TrimsStoreIssue $issue
     * @param StockSummaryAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(
        TrimsStoreIssueDetailFormRequest $request,
        TrimsStoreIssue                  $issue,
        StockSummaryAction               $action
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $detail = $issue->details()->create($request->all());
            $action->attachToStockSummary($detail);
            $action->attachToDailyStockSummary($detail);
            DB::commit();

            return response()->json([
                'message' => 'Trims store issue detail stored successfully',
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
     * @param TrimsStoreIssueDetailFormRequest $request
     * @param TrimsStoreIssueDetail $detail
     * @param StockSummaryAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(
        TrimsStoreIssueDetailFormRequest $request,
        TrimsStoreIssueDetail            $detail,
        StockSummaryAction               $action
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $detail->fill($request->all())->save();
            $action->attachToStockSummary($detail);
            $action->attachToDailyStockSummary($detail);
            DB::commit();

            return response()->json([
                'message' => 'Trims store issue detail updated successfully',
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
     * @param TrimsStoreIssueDetail $detail
     * @param StockSummaryAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(
        TrimsStoreIssueDetail $detail,
        StockSummaryAction    $action
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $detail->delete();
            $action->attachToStockSummary($detail);
            $action->attachToDailyStockSummary($detail);
            DB::commit();

            return response()->json([
                'message' => 'Trims store issue detail deleted successfully',
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
