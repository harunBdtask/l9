<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreIssue;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreIssue\TrimsStoreIssue;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreIssue\TrimsStoreIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Requests\TrimsStore\TrimsStoreIssue\TrimsStoreIssueDetailFormRequest;
use SkylarkSoft\GoRMG\Inventory\Services\Formatters\TrimsStoreFormatters\TrimsStoreIssueDetailFormatter;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TrimsStoreIssueDetailsController extends Controller
{
    /**
     * @param TrimsStoreIssue $issue
     * @param TrimsStoreIssueDetailFormatter $formatter
     * @return JsonResponse
     */
    public function getDetails(
        TrimsStoreIssue                $issue,
        TrimsStoreIssueDetailFormatter $formatter
    ): JsonResponse {
        try {
            $loadData = [
                'details',
                'details.trimsBinCardDetail.mrrDetail',
                'details.itemGroup',
                'details.color',
                'details.uom',
                'details.floor',
                'details.room',
                'details.rack',
                'details.shelf',
                'details.bin',
            ];

            $issue->load($loadData);

            $details = $issue->getRelation('details')
                ->map(function ($detail) use ($formatter) {
                    return $formatter->format($detail);
                });

            return response()->json([
                'message' => 'Fetch Trims Issue Details Successfully',
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
     * @param TrimsStoreIssueDetail $detail
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(
        TrimsStoreIssueDetailFormRequest $request,
        TrimsStoreIssueDetail            $detail
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $detail->fill($request->all())->save();
            DB::commit();

            return response()->json([
                'message' => 'Trims Store Issue Details Updated Successfully',
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
     * @return JsonResponse
     */
    public function destroy(TrimsStoreIssueDetail $detail): JsonResponse
    {
        try {
            $detail->delete();

            return response()->json([
                'message' => 'Trims Store Issue Details Deleted Successfully',
                'data' => $detail,
                'status' => Response::HTTP_NO_CONTENT,
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
}
