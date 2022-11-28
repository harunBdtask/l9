<?php

namespace SkylarkSoft\GoRMG\Commercial\Controllers\lien;

use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\SystemSettings\Models\AdvisingBank;
use Symfony\Component\HttpFoundation\Response;

class AdvisingBankSearchController
{
    public function __invoke(): JsonResponse
    {
        try {
            $advisingBank=AdvisingBank::query()->get(['id','name as text']);
            return response()->json($advisingBank, Response::HTTP_OK);
        }catch (\Exception $exception){
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
