<?php


namespace SkylarkSoft\GoRMG\Merchandising\Transform\PriceQuotation;


use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\Merchandising\Services\OrderService;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;

class PriceQuotationTransformAsOrder implements TransformInterface
{
    public function transform(PriceQuotation $priceQuotation): array
    {
        return [
            'factory_id' => $priceQuotation->factory_id,
//            'job_no' => OrderService::generateUniqueId(),
            'location' => $priceQuotation->location,
            'buyer_id' => $priceQuotation->buyer_id,
            'style_name' => $priceQuotation->style_name,
            'repeated_style' => null,
            'style_description' => $priceQuotation->style_desc,
            'price_quotation_id' => $priceQuotation->id,
            'item_details' => $this->formatItemDetails($priceQuotation->item_details),
            'packing_ratio' => null,
            'product_category_id' => null,
            'product_dept_id' => $priceQuotation->product_department_id,
            'fabrication' => null,
            'order_uom_id' => $priceQuotation->style_uom,
            'smv' => $priceQuotation->sew_smv,
            'region' => $priceQuotation->region,
            'order_copy_from' => $priceQuotation->id,
            'team_leader_id' => null,
            'dealing_merchant_id' => null,
            'factory_merchant_id' => null,
            'season_id' => $priceQuotation->season_id,
            'ship_mode' => null,
            'currency_id' => $priceQuotation->currency_id,
            'repeat_no' => null,
            'buying_agent_id' => null,
            'quality_label' => null,
            'style_owner' => null,
            'shipment_date' => null,
            'po_received_date' => null,
            'lead_time' => null,
            'client' => null,
            'remarks' => null,
            'copy_status' => 1,
            'images' => null,
            'created_by' => $priceQuotation->created_by,
        ];
    }


    private function formatItemDetails($itemDetails): array
    {

        $itemsInfo = array_slice($itemDetails, 0, -1);
        $itemsId = collect($itemsInfo)->pluck('garment_item_id');
        $items = GarmentsItem::query()->whereIn('id', $itemsId)->get()->keyBy('id');
        $format['details'] = collect($itemsInfo)->map(function ($collection) use ($items) {
            return [
                'item_id' => $collection['garment_item_id'],
                'item_smv' => $collection['smv_given'],
                'item_name' => $items[$collection['garment_item_id']]['name'] ?? null,
                'item_ratio' => (float)$collection['item_ratio']
            ];
        })->toArray();

        $calculation = $itemDetails[count($itemDetails) - 1];
        $format['calculation'] = [
            'total_smv' => $calculation['total_smv'],
            'item_count' => $calculation['total_item'],
            'total_item_ratio' => $calculation['total_ratio'],
        ];

        return $format;
    }
}
