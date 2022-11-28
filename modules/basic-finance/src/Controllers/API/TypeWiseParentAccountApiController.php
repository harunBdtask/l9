<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;
use Symfony\Component\HttpFoundation\Response;

class TypeWiseParentAccountApiController extends Controller
{

    const YES = 1;

    public function __invoke($typeId): JsonResponse
    {
        try {
            $accounts = Account::query()
                ->where('type_id', $typeId)
                ->where('is_transactional', self::YES)
                ->where('is_active', self::YES)
                ->get(['id', 'name as text', 'type_id']);

            return response()->json([
                'message' => 'Fetch type wise parent accounts',
                'data' => $accounts,
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

}
