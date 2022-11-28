<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Services\PriceQuotationService;
use SkylarkSoft\GoRMG\SystemSettings\Models\CommercialCostMethod;

class CommercialCostController extends Controller
{
    /**
     * @var PriceQuotationService
     */
    private $priceQuotationService;

    public function __construct(PriceQuotationService $priceQuotationService)
    {
        $this->priceQuotationService = $priceQuotationService;
    }

    public function save(Request $request)
    {
        $message = $this->priceQuotationService->save($request);

        return response()->json([
            'message' => $message,
        ]);
    }

    public function getTypes(): \Illuminate\Http\JsonResponse
    {
        return response()->json(CommercialCostMethod::all());
    }

    public function oldData($pqId, $type)
    {
        $data = $this->priceQuotationService->findOldData($pqId, $type);

        return response()->json($data);
    }
}
