<?php

namespace SkylarkSoft\GoRMG\DyesStore\Services;

class General extends Store
{
    public function requisitions()
    {
        return Requisition::pluck('id');
    }

    public function purchaseRequisitions()
    {
        return GeneralPurchaseRequisition::pluck('requisition_no', 'id');
    }
}
