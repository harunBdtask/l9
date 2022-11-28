<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Orders\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\MerchandisingVariableSettings;
use Symfony\Component\HttpFoundation\Response;

class PriceQuotationFilterApiController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $factoryId = $request->get('factory_id');
            $buyerId = $request->get('buyer_id');

            $quotations = PriceQuotation::with(['factory', 'buyer'])
                ->when($request->get('factory_id'), function ($query) use ($request) {
                    $query->where("factory_id", $request->get('factory_id'));
                })
                ->when($request->get('buyer_id'), function ($query) use ($request) {
                    $query->where("buyer_id", $request->get('buyer_id'));
                })
                ->when($request->get('quotation_id'), function ($query) use ($request) {
                    $query->where("quotation_id", $request->get('quotation_id'));
                })
                ->when($request->get('style'), function ($query) use ($request) {
                    $query->where("style_name", "LIKE", "%{$request->get('style')}%");
                })
                ->when($this->variableCheck($factoryId, $buyerId), function ($query) {
                    $query->where("is_approve", 1);
                })
                ->orderBy('id', 'DESC')->get()->map(function ($value) {
                    return $this->format($value);
                });

            return response()->json($quotations, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $value
     * @return array
     */
    public function format($value)
    {
        $items = collect($value->item_details)->take(count($value->item_details) - 1);
        $items_id = collect($items)->pluck("garment_item_id");
        $garments_items = GarmentsItem::query()->whereIn("id", $items_id)->get();
        $items_data = $items->map(function ($item) use ($garments_items) {
            $item_name = collect($garments_items)->where("id", $item['garment_item_id'])->first();

            return [
                "item_id" => $item['garment_item_id'],
                "item_name" => $item_name['name'],
                "item_ratio" => (float)$item['item_ratio'],
                "item_smv" => (float)$item['smv'],
                "smv_given" => (float)$item['smv_given'],
            ];
        })->toArray();
        return Arr::add($value, "items", $items_data);
    }

    public function variableCheck($factoryId, $buyerId): bool
    {
        $variableSettings = MerchandisingVariableSettings::query()
            ->where([
                'factory_id' => $factoryId,
                'buyer_id' => $buyerId,
            ])->first();

        return isset($variableSettings->variables_details['price_quotation_approval_maintain']) &&
            $variableSettings->variables_details['price_quotation_approval_maintain'] == 1;

    }
}
