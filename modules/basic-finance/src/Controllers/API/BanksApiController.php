<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\BasicFinance\Models\Bank;

class BanksApiController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            $banks = Bank::query()->get()->map(function ($bank) {
                return [
                    'id' => $bank->id,
                    'text' => $bank->account->name,
                    'short_name' => $bank->short_name,
                ];
            });

            return response()->json($banks, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
