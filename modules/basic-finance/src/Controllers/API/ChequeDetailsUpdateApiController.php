<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ChequeDetailsUpdateApiController extends Controller
{
    public function __invoke($chequeId,$to,$amount,$trnDate,$dueDate ): JsonResponse
    {
        try {
            $affected = DB::table('bf_cheque_book_details')
                ->where('id', $chequeId)
                ->update([
                    'status' => 4,
                    'paid_to' => $to,
                    'amount' => $amount,
                    'cheque_date' => $trnDate,
                    'cheque_due_date' => $dueDate

                    ]);
            return response()->json($affected, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
