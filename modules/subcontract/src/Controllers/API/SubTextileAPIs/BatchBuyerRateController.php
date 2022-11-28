<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\API\SubTextileAPIs;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\BatchBuyerRate;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatch;
use Symfony\Component\HttpFoundation\Response;

class BatchBuyerRateController extends Controller
{
    /**
     * @param SubDyeingBatch $dyeingBatch
     * @return JsonResponse
     */
    public function show(SubDyeingBatch $dyeingBatch): JsonResponse
    {
        return response()->json([
            'data' => [
                'buyer_rate' => $dyeingBatch->buyer_rate,
            ],
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }

    /**
     * @param SubDyeingBatch $dyeingBatch
     * @param Request $request
     * @return JsonResponse
     */
    public function update(SubDyeingBatch $dyeingBatch, Request $request): JsonResponse
    {
        try {
            $rate = BatchBuyerRate::query()->where('batch_id', $dyeingBatch->id)
                ->where('dia_type_id', $request->get('dia_type'))
                ->first();

            if ($rate) {
                $rate->update([
                    'rate' => $request->get('buyer_rate'),
                ]);

                return response()->json([
                    'data' => $rate,
                    'status' => Response::HTTP_CREATED,
                ], Response::HTTP_CREATED);
            } else {
                $buyerRate = new BatchBuyerRate();
                $buyerRate->batch_id = $dyeingBatch->id;
                $buyerRate->dia_type_id = $request->get('dia_type');
                $buyerRate->rate = $request->get('buyer_rate');
                $buyerRate->save();

                return response()->json([
                    'data' => $buyerRate,
                    'status' => Response::HTTP_CREATED,
                ], Response::HTTP_CREATED);
            }
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => Response::HTTP_BAD_REQUEST,
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
