<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;

class QuotationItemsApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param $quotation
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke($quotation): \Illuminate\Http\JsonResponse
    {
        try {
            $quotation_data = PriceQuotation::where("quotation_id", $quotation)->first();
            $item_id = collect($quotation_data->item_details)->pluck("garment_item_id")->filter(function ($item) {
                return $item != null;
            })->toArray();
            $items['items'] = GarmentsItem::whereIn("id", $item_id)->get(['id', 'name']);
            $items['quotations'] = $quotation_data;

            return response()->json($items, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), $exception->getCode());
        }
    }
}
