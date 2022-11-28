<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\Merchandising\Models\CostingDetails;
use SkylarkSoft\GoRMG\Merchandising\Services\PriceQuotationService;
use SkylarkSoft\GoRMG\SystemSettings\Models\CostingTemplate;
use SkylarkSoft\GoRMG\SystemSettings\Models\EmbellishmentItem;

class EmbellishmentCostController extends Controller
{
    private $priceQuotationService;

    protected $embl_names;
    protected $printing;
    protected $embroidery;
    protected $special_works;
    protected $gmts_dyeing;
    protected $wash;

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

    public function save(Request $request)
    {
        $data['details'] = $request->get('dataSummery');
        $data['calculation'] = $request->get('calculation');
        $emble['details'] = $data;
        $emble['type'] = $request->get('type');
        $emble['price_quotation_id'] = $request->get('price_quotation_id');

        $quotation = CostingDetails::where('price_quotation_id', $emble['price_quotation_id'])->where('type', $emble['type'])->first();

        if ($quotation) {
            CostingDetails::find($quotation->id)->update($emble);
        } else {
            CostingDetails::create($emble);
        }

        if ($request->get('is_template')) {
            $emble['factory_id'] = $request->get('factory_id');
            $emble['buyer_id'] = $request->get('buyer_id');
            $emble['template_name'] = $request->get('template_name');
            CostingTemplate::create($emble);
        }
    }

    public function oldData($pqId, $type): \Illuminate\Http\JsonResponse
    {
        $data = $this->priceQuotationService->findOldData($pqId, $type);

        return response()->json($data);
    }

    public function loadNames(): \Illuminate\Http\JsonResponse
    {
        $items = EmbellishmentItem::groupBy('name')->get()->filter(function ($value) {
            return $value->name !== $this->wash;
        });

        return response()->json($items);
    }

    public function getNames(): \Illuminate\Http\JsonResponse
    {
        try {
            $items = EmbellishmentItem::groupBy('name')->get();

            return response()->json($items, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function loadNamesWiseData($name): \Illuminate\Http\JsonResponse
    {
        $type = EmbellishmentItem::where('name', $name)->get();

        return response()->json($type);
    }
}
