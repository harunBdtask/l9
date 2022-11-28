<?php

namespace SkylarkSoft\GoRMG\Approval\Services;

use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class PoApprovalUpdateService
{
    public function approvePurchaseOrders($for, $poNos, $type): bool
    {
        $pos = PurchaseOrder::query()->whereIn('po_no', $poNos);

        $pos->update([
            'is_approved' => $type == 1 ?: null,
            'approve_date' => $type == 1 ? date('Y-m-d') : null,
        ]);

        $pos->each(function ($po) use ($type, $for) {
            ApprovalDetailService::for($for)
                ->setPriority(1)
                ->setType($type == 2 ? 0 : 1)
                ->setId($po->id)
                ->store();
        });
        return true;
    }

    public function unapprovePurchaseOrders($for, $poNos): bool
    {
        $pos = PurchaseOrder::query()->whereIn('po_no', $poNos);
        $pos->update([
            'is_approved' => 0,
            'un_approve_request' => null,
        ]);

        $pos->each(function ($po) use ($for) {
            ApprovalDetailService::for($for)
                ->setPriority(1)
                ->setType(0)
                ->setId($po->id)
                ->store();
        });

        return true;
    }
}
