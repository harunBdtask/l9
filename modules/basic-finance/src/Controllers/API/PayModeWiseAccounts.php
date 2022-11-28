<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;
use Symfony\Component\HttpFoundation\Response;

class PayModeWiseAccounts extends Controller
{
    public function __invoke(int $payMode): JsonResponse
    {
        try {
            $accounts = Account::query()->when($payMode == 1, function (Builder $query) {
                    $query->has('parentAc')
                        ->with('parentAc')
                        ->where('is_transactional', 1)
                        ->where('is_active', 1)
                        ->where('type_id', 1)
                        ->where('code','like', '1201002%');
                })->when($payMode == 2, function (Builder $query) {
                $query->has('parentAc')
                    ->with('parentAc')
                    ->where('is_transactional', 1)
                    ->where('is_active', 1)
                    ->where('type_id', 1)
                    ->where('code','like', '1201001%');
                })->get()->map(function ($account) {
                    return [
                        'id' => $account->id,
                        'text' => $account->name. '- (' .$account->parentAc->name. ')',
                        'name' => $account->name,
                        'code' => $account->code,
                    ];
                });
            return response()->json($accounts, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
