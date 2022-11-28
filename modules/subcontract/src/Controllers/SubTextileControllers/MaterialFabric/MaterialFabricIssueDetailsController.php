<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\SubTextileControllers\MaterialFabric;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Subcontract\Actions\StockSummaryAction;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreIssue;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreIssueDetail;
use SkylarkSoft\GoRMG\Subcontract\Requests\SubTextileRequests\FabricIssueDetailsFormRequest;
use SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\IssueDetailsFormatter;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class MaterialFabricIssueDetailsController extends Controller
{
    /**
     * @param SubGreyStoreIssue $greyStoreIssue
     * @param IssueDetailsFormatter $issueDetailsFormatter
     * @return JsonResponse
     */
    public function getDetails(
        SubGreyStoreIssue     $greyStoreIssue,
        IssueDetailsFormatter $issueDetailsFormatter
    ): JsonResponse {
        try {
            return response()->json([
                'message' => 'fabric-receive fetched successfully',
                'data' => $issueDetailsFormatter->format($greyStoreIssue),
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param FabricIssueDetailsFormRequest $request
     * @param SubGreyStoreIssueDetail $subGreyStoreIssueDetail
     * @param StockSummaryAction $stockSummaryAction
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(
        FabricIssueDetailsFormRequest $request,
        SubGreyStoreIssueDetail       $subGreyStoreIssueDetail,
        StockSummaryAction            $stockSummaryAction
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $subGreyStoreIssueDetail->fill($request->all())->save();
            $stockSummaryAction->attachToStockSummaryReport($subGreyStoreIssueDetail);
            $stockSummaryAction->attachToDailyStockSummaryReport($subGreyStoreIssueDetail);
            DB::commit();

            return response()->json([
                'message' => 'Fabric issue details updated successfully',
                'data' => $subGreyStoreIssueDetail,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
