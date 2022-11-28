<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers\Api;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Finance\Models\ChequeBookDetail;

class ChequeNoApiController extends Controller
{
    public function __invoke($bankAccountId): JsonResponse
    {
        try {
            $chequesNo = ChequeBookDetail::query()
                ->whereHas('chequeBook', function (Builder $query) use ($bankAccountId) {
                    $query->where('bank_account_id', $bankAccountId);
                })->where('status', ChequeBookDetail::ACTIVE)
                ->get()->map(function ($chequesNo) {
                    return [
                        'id' => $chequesNo->id,
                        'text' => $chequesNo->cheque_no,
                    ];
                });

            return response()->json($chequesNo, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
