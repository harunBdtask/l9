<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreBinCard;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreBinCard\TrimsStoreBinCard;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreBinCard\TrimsStoreBinCardDetail;
use SkylarkSoft\GoRMG\Inventory\Requests\TrimsStore\TrimsStoreBinCard\TrimsStoreBinCardDetailFormRequest;
use SkylarkSoft\GoRMG\Inventory\Services\Formatters\TrimsStoreFormatters\TrimsStoreBinCardDetailsFormatter;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TrimsBinCardDetailsController extends Controller
{
    /**
     * @param TrimsStoreBinCard $binCard
     * @param TrimsStoreBinCardDetailsFormatter $formatter
     * @return JsonResponse
     */
    public function getDetails(
        TrimsStoreBinCard                 $binCard,
        TrimsStoreBinCardDetailsFormatter $formatter
    ): JsonResponse {
        try {
            $binCard->load('details.issueDetails');

            $details = $binCard->getRelation('details')
                ->map(function ($detail) use ($formatter) {
                    return $formatter->format($detail);
                });

            return response()->json([
                'message' => 'Fetch Trims Bin Card Details Successfully',
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
     * @param TrimsStoreBinCardDetailFormRequest $request
     * @param TrimsStoreBinCardDetail $detail
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(
        TrimsStoreBinCardDetailFormRequest $request,
        TrimsStoreBinCardDetail            $detail
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $detail->fill($request->all())->save();
            DB::commit();

            return response()->json([
                'message' => 'Trims Bin Card Details Updated Successfully',
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
     * @param TrimsStoreBinCardDetail $detail
     * @return JsonResponse
     */

    public function destroy(TrimsStoreBinCardDetail $detail): JsonResponse
    {
        try {
            $detail->delete();

            return response()->json([
                'message' => 'Trims Store Bin Card Detail Deleted Successfully',
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
