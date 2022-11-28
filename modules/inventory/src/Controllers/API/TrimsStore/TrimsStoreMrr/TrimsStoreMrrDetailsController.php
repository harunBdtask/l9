<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreMrr;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreMrr\TrimsStoreMrr;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreMrr\TrimsStoreMrrDetail;
use SkylarkSoft\GoRMG\Inventory\Requests\TrimsStore\TrimsStoreMrr\TrimsStoreMrrDetailFormRequest;
use SkylarkSoft\GoRMG\Inventory\Services\Formatters\TrimsStoreFormatters\TrimsStoreMrrDetailFormatter;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TrimsStoreMrrDetailsController extends Controller
{
    /**
     * @param TrimsStoreMrr $mrr
     * @param TrimsStoreMrrDetailFormatter $formatter
     * @return JsonResponse
     */
    public function getDetails(
        TrimsStoreMrr                $mrr,
        TrimsStoreMrrDetailFormatter $formatter
    ): JsonResponse
    {
        try {
            $loadData = [
                'details',
                'details.itemGroup',
                'details.color',
                'details.uom',
            ];

            $mrr->load($loadData);

            $details = $mrr->getRelation('details')
                ->map(function ($detail) use ($formatter) {
                    return $formatter->format($detail);
                });

            return response()->json([
                'message' => 'Fetch Trims MRR Details Successfully',
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
     * @param Request $request
     * @param TrimsStoreMrr $mrr
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(Request $request, TrimsStoreMrr $mrr): JsonResponse
    {
        try {
            DB::beginTransaction();
            $mrr->details()->createMany($request->all());
            DB::commit();

            return response()->json([
                'message' => 'Trims Store MRR Details Stored Successfully',
                'data' => $mrr->load('details')->getRelation('details'),
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
     * @param TrimsStoreMrrDetailFormRequest $request
     * @param TrimsStoreMrrDetail $detail
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(
        TrimsStoreMrrDetailFormRequest $request,
        TrimsStoreMrrDetail              $detail
    ): JsonResponse
    {
        try {
            DB::beginTransaction();
            $detail->fill($request->all())->save();
            DB::commit();

            return response()->json([
                'message' => 'Trims Store MRR Details Updated Successfully',
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
     * @param TrimsStoreMrrDetail $detail
     * @return JsonResponse
     */
    public function destroy(TrimsStoreMrrDetail $detail): JsonResponse
    {
        try {
            $detail->delete();

            return response()->json([
                'message' => 'Trims Store MRR Details Deleted Successfully',
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
