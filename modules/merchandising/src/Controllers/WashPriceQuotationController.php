<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\Merchandising\Services\PriceQuotationService;
use SkylarkSoft\GoRMG\SystemSettings\Models\EmbellishmentItem;

class WashPriceQuotationController extends Controller
{
    protected $embl_names;
    protected $printing;
    protected $embroidery;
    protected $special_works;
    protected $gmts_dyeing;
    protected $wash;
    private $priceQuotationService;

    public function __construct(PriceQuotationService $priceQuotationService)
    {
        $this->priceQuotationService = $priceQuotationService;
        $this->embl_names = EmbellishmentItem::EMBL_NAMES;
        $this->printing = EmbellishmentItem::PRINTING;
        $this->embroidery = EmbellishmentItem::EMBROIDERY;
        $this->special_works = EmbellishmentItem::SPECIAL_WORKS;
        $this->gmts_dyeing = EmbellishmentItem::GMTS_DYEING;
        $this->wash = EmbellishmentItem::WASH;
    }

    public function save(Request $request): JsonResponse
    {
        $message = $this->priceQuotationService->save($request);

        return response()->json([
            'message' => $message,
        ]);
    }

    public function oldData($pqId, $type): JsonResponse
    {
        $data = $this->priceQuotationService->findOldData($pqId, $type);

        return response()->json($data);
    }

    public function loadNames(): JsonResponse
    {
        $items = EmbellishmentItem::groupBy('name')->get()->reject(function ($value, $key) {
            return $value->name != $this->wash;
        })->flatten();

        return response()->json($items);
    }

    public function loadNamesWiseData($name): JsonResponse
    {
        $type = EmbellishmentItem::where('name', $name)->get();

        return response()->json($type);
    }

    public function loadWashName($name): JsonResponse
    {
        $names = EmbellishmentItem::query()->where('name', $name)->first();
        return response()->json($names, Response::HTTP_OK);
    }
}
