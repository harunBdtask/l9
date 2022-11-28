<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalsReceive;
use Symfony\Component\HttpFoundation\Response;

class DyesChemicalReceiveDetailApiController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $systemGenerateId = $request->get('system_generate_id');
            $challanNo = $request->get('challan_no');
            $supplierId = $request->get('supplier_id');
            $dyesChemical = DyesChemicalsReceive::query()
                ->when($systemGenerateId, function (Builder $query) use ($systemGenerateId) {
                    $query->where('id', $systemGenerateId);
                })->when($challanNo, function (Builder $query) use ($challanNo) {
                    $query->where('reference_no', $challanNo);
                })->when($supplierId, function (Builder $query) use ($supplierId) {
                    $query->where('supplier_id', $supplierId);
                })
                ->first();

            $details = collect($dyesChemical->details)->map(function ($detail) {
                return array_merge($detail, [
                    'return_qty' => null
                ]);
            });

            return response()->json([
                'message' => 'Fetch Successfully',
                'data' => $details,
                'status' => Response::HTTP_OK
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
