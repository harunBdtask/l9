<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Services\Formatters;

class ProcurementRequisitionFormatter
{

    public function format($procurementRequisition)
    {
        $procurementRequisition->load('procurementRequisitionDetails');

        return array_merge($procurementRequisition->toArray(), [
            'created_by_name' => $procurementRequisition->createdBy->screen_name,
            'procurement_requisition_details' => $procurementRequisition
                ->getRelation('procurementRequisitionDetails')
                ->map(function ($collection) {
                    $brand = $collection->brand;
                    $brandName = null;

                    if (isset($brand)) {
                        $brandName = isset($brand->name) && $brand->name != null
                            ? $brand->name
                            : $brand->brand_name;
                    }

                    return array_merge($collection->toArray(), [
                        'item_category' => $collection->itemCategory->name,
                        'item_name' => $collection->item->name,
                        'brand_name' => $brandName,
                        'uom_name' => $collection->uom->name,
                    ]);
                })
        ]);
    }
}
