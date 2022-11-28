<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;

class TypeWiseAccountsApiController extends Controller
{

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $accountType = $request->input('account_type');
            $typeId = $request->input('type_id');
            $parentAccountId = $request->input('id');

            $accounts = Account::query()->get()
                ->map(function ($collection) {
                    $code = trim($collection->code, 0);

                    if (strlen($code) > 0 && strlen($code) < 3) {
                        $collection['account_type'] = 'parent';
                    } elseif (strlen($code) > 2 && strlen($code) < 5) {
                        $collection['account_type'] = 'group';
                    } elseif (strlen($code) > 4 && strlen($code) < 8) {
                        $collection['account_type'] = 'control';
                    } elseif (strlen($code) > 7 && strlen($code) < 11) {
                        $collection['account_type'] = 'ledger';
                    }

                    return [
                        'id' => $collection->id,
                        'name' => $collection->name,
                        'code' => $collection->code,
                        'type_id' => $collection->type_id,
                        'parent_ac' => $collection->parent_ac,
                        'account_type' => $collection->account_type,
                    ];
                })
                ->when($accountType, function ($collection) use ($accountType) {
                    return $collection->where('account_type', $accountType);
                })
                ->when($typeId, function ($collection) use ($typeId) {
                    return $collection->where('type_id', $typeId);
                })
                ->when($parentAccountId, function ($collection) use ($parentAccountId) {
                    return $collection->where('parent_ac', $parentAccountId);
                })
                ->whereNotNull('account_type')
                ->values();

            return response()->json([
                'message' => 'Accounts fetch successfully',
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
