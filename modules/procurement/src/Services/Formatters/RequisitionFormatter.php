<?php

namespace SkylarkSoft\GoRMG\Procurement\Services\Formatters;

class RequisitionFormatter
{
    public function format($procurementRequisition)
    {
        $procurementRequisition->load('procurementRequisitionDetails');

        return array_merge($procurementRequisition->toArray(), [
            // 'created_by_name' => $procurementRequisition->createdBy->screen_name,
            'procurement_requisition_details' => $procurementRequisition
                ->getRelation('procurementRequisitionDetails')
                ->map(function ($collection) {
                    return array_merge($collection->toArray(), [
                        'item_name' => $collection->item->item_group,
                        'uom_name' => $collection->uom->unit_of_measurement,
                    ]);
                }),
        ]);
    }
}
