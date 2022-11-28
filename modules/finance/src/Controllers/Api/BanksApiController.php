<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Finance\Models\Bank;
use Symfony\Component\HttpFoundation\Response;

class BanksApiController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            $banks = Bank::query()->get()->map(function ($bank) {
                return [
                    'id' => $bank->id,
                    'text' => $bank->name,
                    'short_name' => $bank->short_name,
                ];
            });

            return response()->json($banks, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
