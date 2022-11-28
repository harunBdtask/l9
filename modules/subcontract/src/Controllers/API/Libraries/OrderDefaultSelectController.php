<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\API\Libraries;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use Symfony\Component\HttpFoundation\Response;

class OrderDefaultSelectController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $data['factory'] = factoryId();
        $data['payment_basis'] = 1;
        $data['receive_date'] = date('Y-m-d');
        $data['currency'] = Currency::query()
                ->where('currency_name', 'USD')
                ->first()->id ?? 1;

        return response()->json($data, Response::HTTP_OK);
    }
}
