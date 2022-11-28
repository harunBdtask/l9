<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Variables;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\SystemSettings\Models\MerchandisingVariableSettings;

class VariableController extends Controller
{
    public function load($factoryId, $buyerId): \Illuminate\Http\JsonResponse
    {
        $variable = MerchandisingVariableSettings::where('factory_id', $factoryId)->where('buyer_id', $buyerId)->first();

        return response()->json($variable, Response::HTTP_OK);
    }
}
