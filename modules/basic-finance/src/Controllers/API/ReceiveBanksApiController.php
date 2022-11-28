<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use Exception;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\BasicFinance\Models\ReceiveBank;

class ReceiveBanksApiController extends Controller
{
    public function getReceiveBanks()
    {
        try {
            $banks = ReceiveBank::query()->orderBy('name')->get(['id', 'name as text']);

            return response()->json($banks);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
