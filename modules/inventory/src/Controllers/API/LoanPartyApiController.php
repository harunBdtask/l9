<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use Symfony\Component\HttpFoundation\Response;

class LoanPartyApiController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            $loanParty = Supplier::LOAN_PARTY;
            $loanParties = Supplier::query()
                ->where('party_type', 'LIKE', "%{$loanParty}%")
                ->get([
                    'id',
                    'name as text',
                ]);
            return response()->json($loanParties, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
