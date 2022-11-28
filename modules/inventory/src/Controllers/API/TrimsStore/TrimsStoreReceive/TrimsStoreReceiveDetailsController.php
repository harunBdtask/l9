<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreReceive;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreReceive\TrimsStoreReceive;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreReceive\TrimsStoreReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\Requests\TrimsStore\TrimsStoreReceive\TrimsStoreReceiveDetailFormRequest;
use SkylarkSoft\GoRMG\Inventory\Services\Formatters\TrimsStoreFormatters\TrimsStoreReceiveDetailFormatter;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TrimsStoreReceiveDetailsController extends Controller
{
    /**
     * @param TrimsStoreReceive $receive
     * @param TrimsStoreReceiveDetailFormatter $formatter
     * @return JsonResponse
     */
    public function getDetails(
        TrimsStoreReceive                $receive,
        TrimsStoreReceiveDetailFormatter $formatter
    ): JsonResponse {
        try {
            $receive->load('details');

            $details = $receive->getRelation('details')
                ->map(function ($detail) use ($formatter) {
                    return $formatter->format($detail);
                });

            return response()->json([
                'message' => 'Fetch Trims Receive Details Successfully',
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
     * @param TrimsStoreReceiveDetailFormRequest $request
     * @param TrimsStoreReceiveDetail $detail
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(
        TrimsStoreReceiveDetailFormRequest $request,
        TrimsStoreReceiveDetail            $detail
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $detail->fill($request->all())->save();
            DB::commit();

            return response()->json([
                'message' => 'Trims Receive Details Updated Successfully',
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
     * @param TrimsStoreReceiveDetail $detail
     * @return JsonResponse
     */
    public function destroy(TrimsStoreReceiveDetail $detail): JsonResponse
    {
        try {
            $detail->delete();

            return response()->json([
                'message' => 'Trims Receive Detail Deleted Successfully',
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
