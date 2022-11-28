<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;

class YarnAPIController extends Controller
{
    public function getCurrencyIdByName($name): JsonResponse
    {
        $currency = Currency::query()->where('currency_name', $name)->first();

        return response()->json($currency);
    }
}
