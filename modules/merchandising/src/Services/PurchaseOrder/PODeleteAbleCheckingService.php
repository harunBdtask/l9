<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\PurchaseOrder;

use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class PODeleteAbleCheckingService
{
    protected $purchaseOrder;

    public function __construct(PurchaseOrder $purchaseOrder)
    {
        $this->purchaseOrder = $purchaseOrder;
    }

    public function action(): array
    {
        $data = [
            'delete_status' => true,
            'status' => 'Success',
            'type' => 'PO Delete',
            'message' => 'Data Deleted Successfully',
        ];
        $bundleExist = $this->hasBundleCards();
        if ($bundleExist) {
            $data['delete_status'] = false;
            $data['status'] = 'Error';
            $data['message'] = 'Cannot delete because Bundle Card already exists in Cutting!';
        }
        return $data;
    }

    private function hasBundleCards()
    {
        return $this->purchaseOrder->allBundleCards()->count();
    }
}