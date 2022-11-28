<?php


namespace SkylarkSoft\GoRMG\Merchandising\Services;


use SkylarkSoft\GoRMG\Merchandising\Models\ASIConsumption\ASIConsumption;

class ASIConsumptionSummaryReportService
{
    public static function reportData($factoryId, $buyerId, $seasonId, $fromDate, $toDate, $styleName)
    {
        $data = ASIConsumption::with('buyer:id,name', 'factory:id,factory_name', 'season:id,season_name',
            'details', 'details.gmtsItem:id,name', 'details.embellishmentName:id,name', 'details.embellishmentType:id,type',
            'details.fabrication:id,yarn_composition', 'details.uom:id,unit_of_measurement', 'details.bodyPart:id,name');
        if ($factoryId) {
            $data->where('factory_id', $factoryId);
        }

        if ($buyerId) {
            $data->where('buyer_id', $buyerId);
        }

        if ($seasonId) {
            $data->where('season_id', $seasonId);
        }

        if ($styleName && $styleName != "null") {
            $data->whereIn('style_name', $styleName);
        }
        if ($fromDate && $toDate) {
            $data->whereBetween('created_at', [date_format(date_create($fromDate), 'Y-m-d'), date_format(date_create($toDate), 'Y-m-d')]);
        }

        return $data->get();
    }

}
