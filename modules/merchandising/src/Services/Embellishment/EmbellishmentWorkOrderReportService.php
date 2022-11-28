<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Embellishment;

use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\WorkOrders\EmbellishmentWorkOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\TermsAndCondition;

class EmbellishmentWorkOrderReportService
{
    public static function mainData($id)
    {
        $workOrder = EmbellishmentWorkOrder::query()->with(
            'buyer:id,name',
            'supplier:id,name',
            'bookingDetails',
            'factory:id,factory_name',
            'bookingDetails.bodyPart:id,name',
            'bookingDetails.embellishment:id,name,type',
            'bookingDetails.embellishmentType:id,name,type',
            'bookingDetails.budget.uom',
        )->find($id);

        return self::formatData($workOrder);
    }

    public static function formatData($workOrder)
    {
        if ($workOrder) {
            $budgetId = collect($workOrder->bookingDetails)->pluck('budget_unique_id')->unique();
            $orders = Order::with('season', 'dealingMerchant', 'priceQuotation:id,revised_no')->whereIn('job_no', $budgetId)->get();
            $budget = Budget::query()->whereIn('job_no', $budgetId)->get();

            $workOrder['issued_by'] = collect($orders)->pluck('dealingMerchant')->map(function ($val) {
                return $val->first_name . ' ' . $val->last_name;
            })->unique()->implode(',');

//            As Per Gmts. Color
            $workOrder['gmtsColorWiseWorkOrder'] = self::sensitivityWiseFormat(collect($workOrder->bookingDetails)->where('sensitivity', '1'));

//            color size sensitivity
            $workOrder['colorSizeSensitivity'] = self::sensitivityWiseFormat(collect($workOrder->bookingDetails)->where('sensitivity', '4'));

            // contrast color sensitivity
            $workOrder['contrastColorWiseWorkOrder'] = self::sensitivityWiseFormat(collect($workOrder->bookingDetails)->where('sensitivity', '2'));

            // no sensitivity
            $workOrder['noSensitivity'] = self::sensitivityWiseFormat(collect($workOrder->bookingDetails)->whereNull('sensitivity'));

            $workOrder['sizeSensitivity'] = self::sensitivityWiseFormat(collect($workOrder->bookingDetails)->where('sensitivity', '3'));

            $workOrder['termsCondition'] = TermsAndCondition::query()->where('page_name', 'embellishment')->get();

            return $workOrder;
        }
    }

    public static function sensitivityWiseFormat($workOrderDetails)
    {
        return $workOrderDetails->map(function ($item) {
            return collect($item->details)->map(function ($value) use ($item) {
                return [
                    'style' => $item['style'] ?? '',
                    'gmts_item' => collect(collect($item->breakdown)['details'])->pluck('item')->unique()->implode(',') ?? '',
                    'body_part' => $item['bodyPart']['name'] ?? '',
                    'embl_name' => $item['embellishment']['name'] ?? '',
                    'embl_type' => $item['embellishmentType']['type'] ?? '',
                    'gmts_color' => $value['color'] ?? '',
                    'size' => $value['size'] ?? '',
                    'item_color' => $value['item_color'] ?? '',
                    'item_size' => $value['item_size'] ?? '',
                    'wo_total_qty' => $value['wo_total_qty'] ?? 0,
                    'rate' => $value['rate'] ?? 0,
                    'amount' => $value['amount'] ?? 0,
                ];
            });
        })->flatten(1);
    }
}
