<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Interfaces;

interface RequisitionInterface
{
    /**
     * @return mixed key value requisition
     */
    public function requisitions();

    /**
     * @return mixed key value requisition purchase requisitions
     */
    public function purchaseRequisitions();
}
